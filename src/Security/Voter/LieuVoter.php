<?php

namespace App\Security\Voter;

use App\Entity\Lieu;
use App\Entity\Participant;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class LieuVoter extends Voter
{
    public const VIEW = 'ACCESS_LIEU';
    public const ADMIN = 'ACCESS_LIEU_ADMIN';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute === self::VIEW) {
            return true; // pas besoin d’un objet Lieu
        }
        if ($attribute === self::ADMIN) {
            return $subject === null || $subject instanceof Lieu;
        }
        return false;
    }

    private function canView(?Lieu $lieu, Participant $user): bool
    {
        // tout utilisateur connecté peut voir un lieu
        return true;
    }

    private function canAdmin(?Lieu $lieu, Participant $user): bool
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

        /** @var Lieu $lieu */
        $lieu = $subject;

        return match ($attribute) {
            self::ADMIN => $this->canAdmin($subject, $user),
            self::VIEW => $this->canView($subject, $user),
            default => false,
        };
    }
}
