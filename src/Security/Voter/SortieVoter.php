<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class SortieVoter extends Voter
{
    public const ACCESS_SORTIE = 'ACCESS_SORTIE';


    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::ACCESS_SORTIE;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        // Vérifie que l'utilisateur a ROLE_USER
        return in_array('ROLE_USER', $user->getRoles(), true);
    }
}
