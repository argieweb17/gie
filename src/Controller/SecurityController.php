<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // If already logged in, redirect based on role
        $user = $this->getUser();
        if ($user instanceof User) {
            $roles = $user->getRoles();
            $isStudent = !in_array('ROLE_ADMIN', $roles)
                && !in_array('ROLE_STAFF', $roles)
                && !in_array('ROLE_FACULTY', $roles)
                && !in_array('ROLE_SUPERIOR', $roles);

            return $this->redirectToRoute($isStudent ? 'evaluation_history' : 'app_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/login/student', name: 'app_student_login', methods: ['GET', 'POST'])]
    public function studentLogin(Request $request, UserRepository $userRepo, Security $security): Response
    {
        // If already logged in
        $user = $this->getUser();
        if ($user instanceof User) {
            return $this->redirectToRoute('evaluation_history');
        }

        $error = null;

        if ($request->isMethod('POST')) {
            $schoolId = trim($request->request->get('school_id', ''));

            if ($schoolId === '') {
                $error = 'Please enter your Student ID.';
            } else {
                $student = $userRepo->findOneBy(['schoolId' => $schoolId]);

                if (!$student) {
                    $error = 'Student ID not found. Please make sure you have submitted an evaluation first via QR code.';
                } else {
                    // Check this is actually a student (no special roles)
                    $roles = $student->getRoles();
                    $isStudent = !in_array('ROLE_ADMIN', $roles)
                        && !in_array('ROLE_STAFF', $roles)
                        && !in_array('ROLE_FACULTY', $roles)
                        && !in_array('ROLE_SUPERIOR', $roles);

                    if (!$isStudent) {
                        $error = 'This ID belongs to a staff/faculty account. Please use the main login.';
                    } else {
                        // Programmatic login — no password needed
                        $security->login($student, 'form_login', 'main');
                        return $this->redirectToRoute('evaluation_history');
                    }
                }
            }
        }

        return $this->render('security/student_login.html.twig', [
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
