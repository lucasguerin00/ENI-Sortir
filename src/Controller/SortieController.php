<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function list(EntityManagerInterface $entityManager): Response
    {
        $sorties = $entityManager->getRepository(Sortie::class)->findAllActive();

        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties,
        ]);
    }

    // Affiche une sortie
    #[Route('/sortie/{id}', name: 'app_sortie_show')]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    // Affiche le formulaire de création des sorties
    #[Route('/sortie/new', name: 'app_sortie_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();

        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        if ($user) {
            // Assigne l'organisateur à l'utilisateur connecté
            $sortie->setIdOrganisateur($user);
        }

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie créée avec succès !');

            return $this->redirectToRoute('app_sortie_list');
        }

        return $this->render('sortie/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Affiche le formulaire de modification des sorties
    #[Route('/sortie/{id}/edit', name: 'app_sortie_edit')]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        // Vérifie que l'utilisateur est connecté et organisateur
        if ($this->getUser() !== $sortie->getIdOrganisateur()) {
            throw $this->createAccessDeniedException('Vous ne pouvez modifier que vos propres sorties.');
        }

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        // Vérifie que l'utilisateur est connecté et organisateur
        if ($this->getUser() !== $sortie->getIdOrganisateur()) {
            throw $this->createAccessDeniedException('Vous ne pouvez annulé que vos propres sorties.');
        }

        // Vérifie le token CSRF pour éviter l'annulation accidentelle
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie annulé avec succès !');
        }

        return $this->redirectToRoute('app_sortie_list');
    }

    // Archive une sortie
    #[Route('/sortie/{id}/archive', name: 'app_sortie_archive', methods: ['POST'])]
    public function archive(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        // Vérifie que l'utilisateur est connecté et organisateur
        if ($this->getUser() !== $sortie->getIdOrganisateur()) {
            throw $this->createAccessDeniedException('Vous ne pouvez archivé que vos propres sorties.');
        }

        if ($this->isCsrfTokenValid('archive'.$sortie->getId(), $request->request->get('_token'))) {
            $sortie->setIsArchived(true);
            $sortie->setArchivedAt(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Sortie archivée avec succès !');
        }

        return $this->redirectToRoute('app_sortie_list');
    }

    //Gère l'inscription à une sortie
    #[Route('/sortie/{id}/inscription', name: 'app_sortie_inscription')]
    public function inscription(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
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

        $this->addFlash('success', 'Inscription réussie !');

        return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()]);
    }

    //Gère la désinscription à une sortie
    #[Route('/sortie/{id}/desinscription', name: 'app_sortie_desinscription')]
    public function desinscription(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour vous désinscrire.');
        }

        if (!$sortie->getParticipants()->contains($user)) {
            $this->addFlash('warning', 'Vous n’êtes pas inscrit à cette sortie.');
            return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()]);
        }

        $sortie->removeParticipant($user);
        $entityManager->flush();

        $this->addFlash('success', 'Vous vous êtes désinscrit de la sortie.');

        return $this->redirectToRoute('app_sortie_show', ['id' => $sortie->getId()]);
    }



}
