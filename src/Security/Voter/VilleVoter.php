<?php

namespace App\Security\Voter;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Ville;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class VilleVoter extends Voter
{
    public const VIEW = 'ACCESS_VILLE';
    public const ADMIN = 'ACCESS_VILLE_ADMIN';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute === self::VIEW) {
            return true; // pas besoin d’un objet Ville
        }
        if ($attribute === self::ADMIN) {
            return $subject === null || $subject instanceof Ville;
        }
        return false;
    }

    private function canView(?Ville $ville, Participant $user): bool
    {
        // tout utilisateur connecté peut voir une Ville
        return true;
    }

    private function canAdmin(?Ville $ville, Participant $user): bool
    {
        // seuls les admins
        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Participant) {
            return false;
        }

        /** @var Ville $ville */
        $ville = $subject;

        return match ($attribute) {
            self::ADMIN => $this->canAdmin($subject, $user),
            self::VIEW => $this->canView($subject, $user),
            default => false,
        };
    }
}
