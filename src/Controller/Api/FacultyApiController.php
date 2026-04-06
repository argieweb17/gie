<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\EvaluationResponseRepository;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class FacultyApiController extends AbstractController
{
    private function requireFacultyUser(Request $request): ?JsonResponse
    {
        $user = $request->attributes->get('_api_user');
        if (!$user instanceof User) {
            return $this->json(['error' => 'Authentication required.'], 401);
        }

        $roles = $user->getRoles();
        if (
            !in_array('ROLE_FACULTY', $roles, true)
            && !in_array('ROLE_SUPERIOR', $roles, true)
            && !in_array('ROLE_ADMIN', $roles, true)
        ) {
            return $this->json(['error' => 'Access denied. Insufficient permissions.'], 403);
        }

        return null;
    }

    #[Route('/faculty/profile', name: 'faculty_profile', methods: ['GET'])]
    public function profile(Request $request): JsonResponse
    {
        $authError = $this->requireFacultyUser($request);
        if ($authError !== null) {
            return $authError;
        }

        $user = $request->attributes->get('_api_user');

        return $this->json([
            'id' => $user->getId(),
            'schoolId' => $user->getSchoolId(),
            'fullName' => $user->getFullName(),
            'email' => $user->getEmail(),
            'department' => $user->getDepartment()?->getDepartmentName(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/faculty/summary', name: 'faculty_summary', methods: ['GET'])]
    public function summary(
        Request $request,
        SubjectRepository $subjectRepo,
        EvaluationResponseRepository $responseRepo
    ): JsonResponse {
        $authError = $this->requireFacultyUser($request);
        if ($authError !== null) {
            return $authError;
        }

        $user = $request->attributes->get('_api_user');

        $subjectCount = count($subjectRepo->findBy(['faculty' => $user]));
        $responseCount = (int) $responseRepo->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.faculty = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return $this->json([
            'subjectCount' => $subjectCount,
            'responseCount' => $responseCount,
        ]);
    }

    #[Route('/my-results', name: 'my_results', methods: ['GET'])]
    public function myResults(
        Request $request,
        EvaluationResponseRepository $responseRepo
    ): JsonResponse {
        $authError = $this->requireFacultyUser($request);
        if ($authError !== null) {
            return $authError;
        }

        $user = $request->attributes->get('_api_user');

        $responses = $responseRepo->createQueryBuilder('r')
            ->select('ep.id as evalId, ep.evaluationType, ep.schoolYear, ep.semester,
                       AVG(r.rating) as avgRating, COUNT(DISTINCT r.evaluator) as evaluatorCount')
            ->join('r.evaluationPeriod', 'ep')
            ->where('r.faculty = :user')
            ->setParameter('user', $user)
            ->groupBy('ep.id, ep.evaluationType, ep.schoolYear, ep.semester')
            ->getQuery()
            ->getResult();

        return $this->json(['results' => $responses]);
    }

    #[Route('/my-subjects', name: 'my_subjects', methods: ['GET'])]
    public function mySubjects(
        Request $request,
        SubjectRepository $subjectRepo
    ): JsonResponse {
        $authError = $this->requireFacultyUser($request);
        if ($authError !== null) {
            return $authError;
        }

        $user = $request->attributes->get('_api_user');

        $subjects = $subjectRepo->findBy(['faculty' => $user]);
        $result = [];
        foreach ($subjects as $subject) {
            $result[] = [
                'id' => $subject->getId(),
                'code' => $subject->getSubjectCode(),
                'name' => $subject->getSubjectName(),
                'semester' => $subject->getSemester(),
                'schoolYear' => $subject->getSchoolYear(),
                'yearLevel' => $subject->getYearLevel(),
                'section' => $subject->getSection(),
                'schedule' => $subject->getSchedule(),
                'units' => $subject->getUnits(),
            ];
        }

        return $this->json(['subjects' => $result]);
    }
}
