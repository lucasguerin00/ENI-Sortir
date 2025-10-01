<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function defaultRoute(EntityManagerInterface $entityManager): Response
    {

        return $this->redirectToRoute('app_sortie_list');
    }

    // Affiche la liste des sorties
    #[Route('/sorties', name: 'app_sortie_list')]
    public function list(Request $request, EntityManagerInterface $entityManager): Response
    {
        $filter = $request->query->get('filter', 'all'); // all par défaut
        $siteFilter = $request->query->get('site', 'all'); // all par défaut
        $user = $this->getUser();

        $sorties = $entityManager->getRepository(Sortie::class)->findAllOrdered();

        // Application des filtres côté PHP
        if ($user) {
            if ($filter === 'mine') {
                $sorties = array_filter($sorties, fn(Sortie $s) => $s->getIdOrganisateur() === $user);
            } elseif ($filter === 'inscrit') {
                $sorties = array_filter($sorties, fn(Sortie $s) => $s->getParticipants()->contains($user));
            } elseif ($filter === 'non_inscrit') {
                $sorties = array_filter($sorties, fn(Sortie $s) => !$s->getParticipants()->contains($user));
            }
        }

        // Filtre par ville
        if ($siteFilter !== 'all') {
            $sorties = array_filter($sorties, fn(Sortie $s) => $s->getIdSite() && $s->getIdSite()->getId() == $siteFilter);
        }

        // Récupération de tous les sites pour alimenter le select
        $sites = $entityManager->getRepository(Site::class)->findAll();

        // Mise à jour des états avant affichage
        foreach ($sorties as $sortie) {
            $this->updateEtat($sortie, $entityManager);
        }
        $entityManager->flush();

        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties,
            'filter' => $filter,
            'siteFilter' => $siteFilter,
            'sites' => $sites,
        ]);
    }

    // Affiche la liste des sorties
    #[Route('/sorties', name: 'app_sortie_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        return $this->redirectToRoute('app_default');
    }

    // Affiche le formulaire de création des sorties
    #[Route('/sortie/new', name: 'app_sortie_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $this->denyAccessUnlessGranted('ACCESS_SORTIE');
        // Récupère l'utilisateur connecté
        $user = $this->getUser();
        if ($user) {
            $sortie->setIdOrganisateur($user);
        }

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Calcule et affecte l’état automatiquement
            $this->updateEtat($sortie, $entityManager);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie créée avec succès !');

            return $this->redirectToRoute('app_sortie_list');
        }

        return $this->render('sortie/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Affiche une sortie
    #[Route('/sortie/{id}', name: 'app_sortie_show')]
    public function show(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {

        $this->updateEtat($sortie, $entityManager);
        $entityManager->flush();

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    // Affiche le formulaire de modification des sorties
    #[Route('/sortie/{id}/edit', name: 'app_sortie_edit')]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_SORTIE');
        // Vérifie que l'utilisateur est connecté et organisateur
        if ($this->getUser() !== $sortie->getIdOrganisateur()) {
            throw $this->createAccessDeniedException('Vous ne pouvez modifier que vos propres sorties.');
        }

        if ($sortie->getEtat()->getLibelle() !== 'Ouvert') {
            $this->addFlash('error', "La sortie n'est plus modifiable car elle n'est plus ouverte.");
            return $this->redirectToRoute('app_sortie_list');
        }

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Recalcule et met à jour l’état
            $this->updateEtat($sortie, $entityManager);

            $entityManager->flush();

            $this->addFlash('success', 'Sortie modifiée avec succès !');

            return $this->redirectToRoute('app_sortie_list');
        }

        return $this->render('sortie/edit.html.twig', [
            'form' => $form->createView(),
            'sortie' => $sortie,
        ]);
    }

    // Annule une sortie
    #[Route('/sortie/{id}/delete', name: 'app_sortie_delete', methods: ['POST'])]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_SORTIE');
        // Vérifie que l'utilisateur est connecté et organisateur
        if ($this->getUser() !== $sortie->getIdOrganisateur()) {
            throw $this->createAccessDeniedException('Vous ne pouvez annuler que vos propres sorties.');
        }

        // Vérifie le token CSRF pour éviter l'annulation accidentelle
        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie annulée avec succès !');
        }

        return $this->redirectToRoute('app_sortie_list');
    }

    // Archive une sortie
    #[Route('/sortie/{id}/archive', name: 'app_sortie_archive', methods: ['POST'])]
    public function archive(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_SORTIE');
        // Vérifie que l'utilisateur est connecté et organisateur
        if ($this->getUser() !== $sortie->getIdOrganisateur()) {
            throw $this->createAccessDeniedException('Vous ne pouvez archiver que vos propres sorties.');
        }

        if ($this->isCsrfTokenValid('archive' . $sortie->getId(), $request->request->get('_token'))) {
            $sortie->setIsArchived(true);
            $sortie->setArchivedAt(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Sortie archivée avec succès !');
        }

        return $this->redirectToRoute('app_sortie_list');
    }

    //Gère l'inscription à une sortie
    #[Route('/sortie/{id}/inscription', name: 'app_sortie_inscription')]
    public function inscription(Sortie $sortie, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_SORTIE');
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour vous inscrire.');
        }

        // Empêcher l’organisateur de s’inscrire à sa propre sortie
        if ($sortie->getIdOrganisateur() === $user) {
            $this->addFlash('warning', 'Vous êtes déjà l’organisateur de cette sortie.');
            return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()]);
        }

        // Vérifier si déjà inscrit
        if ($sortie->getParticipants()->contains($user)) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cette sortie.');
            return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()]);
        }

        // Ajouter l’utilisateur comme participant
        $sortie->addParticipant($user);
        $entityManager->flush();

        // ENVOI DU MAIL
        $email = (new TemplatedEmail())
            ->from('no-reply@sortir.com')
            ->to($user->getMail())
            ->subject('Inscription à la sortie « '.$sortie->getNom().' »')
            ->htmlTemplate('emails/inscription.html.twig')
            ->context([
                'participant' => $user,
                'sortie' => $sortie,
            ]);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('error', 'Le mail de confirmation n’a pas pu être envoyé.');
        }

        $this->addFlash('success', 'Inscription réussie !');
        return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()]);
    }

    //Gère la désinscription à une sortie
    #[Route('/sortie/{id}/desinscription', name: 'app_sortie_desinscription')]
    public function desinscription(Sortie $sortie, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_SORTIE');
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour vous désinscrire.');
        }

        if (!$sortie->getParticipants()->contains($user)) {
            $this->addFlash('warning', 'Vous n’êtes pas inscrit à cette sortie.');
            return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()]);
        }

        // Retirer l’utilisateur
        $sortie->removeParticipant($user);
        $entityManager->flush();

        // ENVOI DU MAIL
        $email = (new TemplatedEmail())
            ->from('no-reply@sortir.com')
            ->to($user->getMail())
            ->subject('Désinscription à la sortie « '.$sortie->getNom().' »')
            ->htmlTemplate('emails/desinscription.html.twig')
            ->context([
                'participant' => $user,
                'sortie' => $sortie,
            ]);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('error', 'Le mail de désinscription n’a pas pu être envoyé.');
        }

        $this->addFlash('success', 'Vous vous êtes désinscrit de la sortie.');
        return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()]);
    }

    /**
     * Met à jour automatiquement l’état d’une sortie
     */
    private function updateEtat(Sortie $sortie, EntityManagerInterface $entityManager): void
    {
        $now = new \DateTime();
        $dateDebut = $sortie->getDateHeureDebut();
        $dateCloture = $sortie->getDateLimiteInscription();
        $dateFin = (clone $dateDebut)->modify("+{$sortie->getDuree()} minutes");

        $etatLibelle = null;

        if ($now < $dateCloture) {
            $etatLibelle = 'Ouvert';
        } elseif ($now >= $dateCloture && $now < $dateDebut) {
            $etatLibelle = 'Fermée';
        } elseif ($now >= $dateDebut && $now <= $dateFin) {
            $etatLibelle = 'En cours';
        } elseif ($now > $dateFin) {
            $etatLibelle = 'Terminée';
        } else {
            $etatLibelle = 'En création';
        }

        $etat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => $etatLibelle]);

        if ($etat) {
            $sortie->setEtat($etat);
        }
    }
}
