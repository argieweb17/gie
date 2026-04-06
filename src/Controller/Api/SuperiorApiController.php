<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\SuperiorEvaluationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/superior')]
class SuperiorApiController extends AbstractController
{
    private function resolveSuperiorUser(Request $request): User|JsonResponse
    {
        $apiUser = $request->attributes->get('_api_user');
        $user = $apiUser instanceof User ? $apiUser : $this->getUser();

        if (!$user instanceof User) {
            return $this->json(['error' => 'Authentication required.'], 401);
        }

        $roles = $user->getRoles();
        if (!in_array('ROLE_SUPERIOR', $roles, true) && !in_array('ROLE_ADMIN', $roles, true)) {
            return $this->json(['error' => 'Access denied. Insufficient permissions.'], 403);
        }

        return $user;
    }

    #[Route('/api/profile', name: 'superior_api_profile', methods: ['GET'])]
    public function profile(Request $request): JsonResponse
    {
        $user = $this->resolveSuperiorUser($request);
        if ($user instanceof JsonResponse) {
            return $user;
        }

        $this->assertDepartmentHeadSuperior($user);

        return $this->json([
            'id' => $user->getId(),
            'schoolId' => $user->getSchoolId(),
            'fullName' => $user->getFullName(),
            'email' => $user->getEmail(),
            'department' => $user->getDepartment()?->getDepartmentName(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/api/summary', name: 'superior_api_summary', methods: ['GET'])]
    public function summary(Request $request, UserRepository $userRepo): JsonResponse
    {
        $user = $this->resolveSuperiorUser($request);
        if ($user instanceof JsonResponse) {
            return $user;
        }

        $this->assertDepartmentHeadSuperior($user);

        $department = $user->getDepartment();
        $evaluatees = $department ? $userRepo->findBy(['department' => $department]) : [];

        $evaluateeCount = 0;
        foreach ($evaluatees as $evaluatee) {
            if ($evaluatee->getId() === $user->getId()) {
                continue;
            }
            if ($evaluatee->isAdmin() || $evaluatee->isStaff()) {
                continue;
            }
            $evaluateeCount++;
        }

        return $this->json([
            'department' => $department ? $department->getDepartmentName() : null,
            'evaluateeCount' => $evaluateeCount,
        ]);
    }

    #[Route('/results/detail', name: 'superior_results_detail', methods: ['GET'])]
    public function resultsDetail(
        Request $request,
        SuperiorEvaluationRepository $superiorEvalRepo,
        UserRepository $userRepo,
    ): JsonResponse {
        $user = $this->resolveSuperiorUser($request);
        if ($user instanceof JsonResponse) {
            return $user;
        }

        $this->assertDepartmentHeadSuperior($user);

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

    private function assertDepartmentHeadSuperior(?User $user): void
    {
        if (!$user instanceof User || $user->isAdmin() || $user->isStaff()) {
            throw $this->createAccessDeniedException('Superior access is restricted to authorized superior accounts.');
        }

        if ($user->hasAssignedRole('ROLE_SUPERIOR')) {
            return;
        }

        if (!$user->isDepartmentHeadFaculty()) {
            throw $this->createAccessDeniedException('Superior access is restricted to authorized superior accounts.');
        }
    }
}
