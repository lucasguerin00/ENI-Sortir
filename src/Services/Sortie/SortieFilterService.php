<?php

namespace App\Services\Sortie;

use App\Entity\Site;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SortieFilterService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    /**
     * Filtre la liste des sorties selon les critères donnés
     */
    public function getFilteredSorties(?UserInterface $user, string $filter, string $siteFilter): array
    {
        // Récupération brute
        $sorties = $this->entityManager->getRepository(Sortie::class)->findAllOrdered();
        // Filtres liés à l'utilisateur
        if ($user) {
            if ($filter === 'mine') {
                $sorties = array_filter($sorties, fn(Sortie $s) => $s->getIdOrganisateur() === $user);
            } elseif ($filter === 'inscrit') {
                $sorties = array_filter($sorties, fn(Sortie $s) => $s->getParticipants()->contains($user));
            } elseif ($filter === 'non_inscrit') {
                $sorties = array_filter($sorties, fn(Sortie $s) => !$s->getParticipants()->contains($user));
            }
        }
        // Filtre par site
        if ($siteFilter !== 'all') {
            $sorties = array_filter($sorties, fn(Sortie $s) =>
                $s->getIdSite() && $s->getIdSite()->getId() == $siteFilter
            );
        }
        return $sorties;
    }

    /**
     * Pour lister tous les sites
     */
    public function getAllSites(): array
    {
        return $this->entityManager->getRepository(Site::class)->findAll();
    }
}
