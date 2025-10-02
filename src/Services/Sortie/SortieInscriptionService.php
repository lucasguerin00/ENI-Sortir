<?php

namespace App\Services\Sortie;

use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SortieInscriptionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer
    ) {}

    public function inscrire(Sortie $sortie, UserInterface $user): string
    {
        if ($sortie->getIdOrganisateur() === $user) {
            return 'Vous êtes déjà l’organisateur de cette sortie.';
        }

        if ($sortie->getParticipants()->contains($user)) {
            return 'Vous êtes déjà inscrit à cette sortie.';
        }

        $sortie->addParticipant($user);
        $this->entityManager->flush();

        $email = (new TemplatedEmail())
            ->from('no-reply@sortir.com')
            ->to($user->getMail())
            ->subject('Inscription à la sortie « '.$sortie->getNom().' »')
            ->htmlTemplate('emails/inscription.html.twig')
            ->context([
                'participant' => $user,
                'sortie' => $sortie,
            ]);
        $this->mailer->send($email);

        return 'Inscription réussie !';
    }

    public function desinscrire(Sortie $sortie, UserInterface $user): string
    {
        if (!$sortie->getParticipants()->contains($user)) {
            return 'Vous n’êtes pas inscrit à cette sortie.';
        }

        $sortie->removeParticipant($user);
        $this->entityManager->flush();

        $email = (new TemplatedEmail())
            ->from('no-reply@sortir.com')
            ->to($user->getMail())
            ->subject('Désinscription à la sortie « '.$sortie->getNom().' »')
            ->htmlTemplate('emails/desinscription.html.twig')
            ->context([
                'participant' => $user,
                'sortie' => $sortie,
            ]);
        $this->mailer->send($email);

        return 'Vous vous êtes désinscrit de la sortie.';
    }
}
