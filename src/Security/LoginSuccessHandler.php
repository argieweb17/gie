<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private RouterInterface $router) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();

        // Students (no special roles) go to evaluation history
        if ($user instanceof User) {
            $roles = $user->getRoles();
            $isStudent = !in_array('ROLE_ADMIN', $roles)
                && !in_array('ROLE_STAFF', $roles)
                && !in_array('ROLE_FACULTY', $roles)
                && !in_array('ROLE_SUPERIOR', $roles);

            if ($isStudent) {
                return new RedirectResponse($this->router->generate('evaluation_history'));
            }
        }

        return new RedirectResponse($this->router->generate('app_dashboard'));
    }
}
