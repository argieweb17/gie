<?php

namespace App\Controller;

use App\Entity\AuditLog;
use App\Entity\SuperiorEvaluation;
use App\Entity\User;
use App\Repository\EvaluationPeriodRepository;
use App\Repository\QuestionRepository;
use App\Repository\SuperiorEvaluationRepository;
use App\Repository\UserRepository;
use App\Repository\DepartmentRepository;
use App\Service\AuditLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/superior')]
#[IsGranted('ROLE_SUPERIOR')]
class SuperiorController extends AbstractController
{
    public function __construct(private AuditLogger $audit) {}

    #[Route('/evaluate', name: 'superior_evaluate_index', methods: ['GET'])]
    public function evaluateIndex(
        EvaluationPeriodRepository $evalRepo,
        UserRepository $userRepo,
        SuperiorEvaluationRepository $superiorEvalRepo,
        DepartmentRepository $deptRepo,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $latestEval = $evalRepo->findLatestOpen('SUPERIOR');
        $openEvals = $latestEval ? [$latestEval] : [];

        // Get evaluatees: only the faculty assigned to each evaluation
        $evaluatees = [];
        foreach ($openEvals as $eval) {
            $facultyName = $eval->getFaculty();
            if (!$facultyName) continue;
            foreach ($userRepo->findAll() as $u) {
                if (!$u->isActive() || $u->getId() === $user->getId()) continue;
                if ($u->getFullName() === $facultyName) {
                    $empStatus = strtolower($u->getEmploymentStatus() ?? '');
                    if (str_contains($empStatus, 'dean')) {
                        $evaluatees[] = ['user' => $u, 'type' => SuperiorEvaluation::TYPE_DEAN, 'label' => 'Dean'];
                    } elseif (str_contains($empStatus, 'head') || str_contains($empStatus, 'chair')) {
                        $evaluatees[] = ['user' => $u, 'type' => SuperiorEvaluation::TYPE_DEPARTMENT_HEAD, 'label' => 'Department Head'];
                    } else {
                        $evaluatees[] = ['user' => $u, 'type' => SuperiorEvaluation::TYPE_DEPARTMENT_HEAD, 'label' => $u->getEmploymentStatus() ?? 'Faculty'];
                    }
                    break;
                }
            }
        }

        // Check submission status for each open eval + evaluatee combination
        $submissions = [];
        foreach ($openEvals as $eval) {
            foreach ($evaluatees as $e) {
                $key = $eval->getId() . '-' . $e['user']->getId();
                $submissions[$key] = $superiorEvalRepo->hasSubmitted($user->getId(), $eval->getId(), $e['user']->getId());
            }
        }

        return $this->render('superior/evaluate_index.html.twig', [
            'openEvals' => $openEvals,
            'evaluatees' => $evaluatees,
            'submissions' => $submissions,
            'departments' => $deptRepo->findAllOrdered(),
        ]);
    }

    #[Route('/evaluate/{evalId}/{evaluateeId}', name: 'superior_evaluate_form', methods: ['GET', 'POST'])]
    public function evaluateForm(
        int $evalId,
        int $evaluateeId,
        Request $request,
        EvaluationPeriodRepository $evalRepo,
        UserRepository $userRepo,
        QuestionRepository $questionRepo,
        SuperiorEvaluationRepository $superiorEvalRepo,
        EntityManagerInterface $em,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $eval = $evalRepo->find($evalId);
        $evaluatee = $userRepo->find($evaluateeId);

        if (!$eval || !$evaluatee || !$eval->isOpen()) {
            $this->addFlash('danger', 'Invalid evaluation or it is closed.');
            return $this->redirectToRoute('superior_evaluate_index');
        }

        // Determine evaluatee role
        $empStatus = strtolower($evaluatee->getEmploymentStatus() ?? '');
        $evaluateeRole = SuperiorEvaluation::TYPE_DEPARTMENT_HEAD;
        if (str_contains($empStatus, 'dean')) {
            $evaluateeRole = SuperiorEvaluation::TYPE_DEAN;
        }

        // Already submitted?
        if ($superiorEvalRepo->hasSubmitted($user->getId(), $evalId, $evaluateeId)) {
            $this->addFlash('info', 'You have already submitted this evaluation.');
            return $this->redirectToRoute('superior_evaluate_index');
        }

        // Get questions (use SEF type for superior evaluations)
        $questions = $questionRepo->findBy(
            ['evaluationType' => 'SEF', 'isActive' => true],
            ['category' => 'ASC', 'sortOrder' => 'ASC']
        );

        // Load existing drafts
        $drafts = $superiorEvalRepo->findDrafts($user->getId(), $evalId, $evaluateeId);
        $draftMap = [];
        foreach ($drafts as $d) {
            $draftMap[$d->getQuestion()->getId()] = [
                'rating' => $d->getRating(),
                'comment' => $d->getComment(),
            ];
        }

        if ($request->isMethod('POST')) {
            $action = $request->request->get('_action', 'submit');
            $ratings = $request->request->all('ratings');
            $comments = $request->request->all('comments');
            $generalComment = trim($comments[0] ?? '');
            $commentSaved = false;
            $isDraft = ($action === 'save_draft');

            // Remove existing drafts
            foreach ($drafts as $d) {
                $em->remove($d);
            }
            $em->flush();

            foreach ($questions as $q) {
                $rating = (int) ($ratings[$q->getId()] ?? 0);
                if ($rating < 1 || $rating > 5) {
                    if (!$isDraft) continue;
                    $rating = 0;
                }

                $response = new SuperiorEvaluation();
                $response->setEvaluationPeriod($eval);
                $response->setEvaluator($user);
                $response->setEvaluatee($evaluatee);
                $response->setEvaluateeRole($evaluateeRole);
                $response->setQuestion($q);
                $response->setRating($rating);
                // Attach the general comment to the first response
                if (!$commentSaved && $generalComment !== '') {
                    $response->setComment($generalComment);
                    $commentSaved = true;
                }
                $response->setIsDraft($isDraft);
                $response->setSubmittedAt(new \DateTime());

                $em->persist($response);
            }

            $em->flush();

            if ($isDraft) {
                $this->audit->log(AuditLog::ACTION_SAVE_DRAFT, 'SuperiorEvaluation', $evalId,
                    'Draft saved for ' . $evaluatee->getFullName());
                $this->addFlash('info', 'Draft saved successfully.');
            } else {
                $this->audit->log(AuditLog::ACTION_SUBMIT_SET, 'SuperiorEvaluation', $evalId,
                    'Superior evaluation submitted for ' . $evaluatee->getFullName());
                $this->addFlash('success', 'Evaluation submitted successfully.');
            }

            return $this->redirectToRoute('superior_evaluate_index');
        }

        // Group questions by category
        $grouped = [];
        foreach ($questions as $q) {
            $cat = $q->getCategory() ?? 'General';
            $grouped[$cat][] = $q;
        }

        return $this->render('superior/evaluate_form.html.twig', [
            'eval' => $eval,
            'evaluatee' => $evaluatee,
            'evaluateeRole' => $evaluateeRole,
            'groupedQuestions' => $grouped,
            'draftMap' => $draftMap,
        ]);
    }

    #[Route('/results', name: 'superior_results', methods: ['GET'])]
    public function results(
        EvaluationPeriodRepository $evalRepo,
        SuperiorEvaluationRepository $superiorEvalRepo,
        UserRepository $userRepo,
        DepartmentRepository $deptRepo,
    ): Response {
        $allEvals = $evalRepo->findAllOrdered();
        $selectedEvalId = null;
        $rankings = [];

        if (!empty($allEvals)) {
            $selectedEvalId = $allEvals[0]->getId();
            $rawRankings = $superiorEvalRepo->getEvaluateeRankings($selectedEvalId);

            foreach ($rawRankings as $r) {
                $evaluatee = $userRepo->find($r['evaluateeId']);
                if ($evaluatee) {
                    $avg = round((float) $r['avgRating'], 2);
                    $level = match (true) {
                        $avg >= 4.5 => 'Excellent',
                        $avg >= 3.5 => 'Very Good',
                        $avg >= 2.5 => 'Good',
                        $avg >= 1.5 => 'Fair',
                        default => 'Poor',
                    };
                    $rankings[] = [
                        'evaluatee' => $evaluatee,
                        'average' => $avg,
                        'evaluators' => (int) $r['evaluatorCount'],
                        'level' => $level,
                    ];
                }
            }
        }

        return $this->render('superior/results.html.twig', [
            'allEvals' => $allEvals,
            'selectedEvalId' => $selectedEvalId,
            'rankings' => $rankings,
            'departments' => $deptRepo->findAllOrdered(),
        ]);
    }

    #[Route('/results/detail', name: 'superior_results_detail', methods: ['GET'])]
    public function resultsDetail(
        Request $request,
        SuperiorEvaluationRepository $superiorEvalRepo,
        UserRepository $userRepo,
    ): JsonResponse {
        $evalId = (int) $request->query->get('evalId');
        $evaluateeId = (int) $request->query->get('evaluateeId');

        $evaluatee = $userRepo->find($evaluateeId);
        if (!$evaluatee) {
            return $this->json(['error' => 'Not found'], 404);
        }

        $catAvgs = $superiorEvalRepo->getCategoryAverages($evaluateeId, $evalId);
        $comments = $superiorEvalRepo->getComments($evaluateeId, $evalId);
        $overall = $superiorEvalRepo->getOverallAverage($evaluateeId, $evalId);

        return $this->json([
            'evaluatee' => $evaluatee->getFullName(),
            'department' => $evaluatee->getDepartment() ? $evaluatee->getDepartment()->getDepartmentName() : '—',
            'overall' => $overall,
            'categories' => $catAvgs,
            'comments' => array_column($comments, 'comment'),
        ]);
    }
}
