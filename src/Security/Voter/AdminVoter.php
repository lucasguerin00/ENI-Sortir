<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class AdminVoter extends Voter
{
    public const ACCESS_ADMIN = 'ACCESS_ADMIN';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::ACCESS_ADMIN;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        // Vérifie que l'utilisateur a ROLE_ADMIN
        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
