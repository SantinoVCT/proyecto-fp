<?php

namespace App\Security;

use App\Entity\Usuario;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Usuario) {
            return;
        }

        if (!$user->isVerified()) {
            throw new CustomUserMessageAuthenticationException('Debes confirmar tu correo electrónico antes de iniciar sesión.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
