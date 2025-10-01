<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VilleController extends AbstractController
{
    #[Route('/ville', name: 'app_ville')]
    public function index(VilleRepository $villeRepository): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_VILLE');
        $villes=$villeRepository->findAll();
        return $this->render('ville/index.html.twig', [
            'villes' => $villes,
            'controller_name' => 'VilleController',
        ]);
    }

    #[Route('/ville/add', name: 'app_ville_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_VILLE_ADMIN');
        $ville = new Ville();

        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'Ville créée avec succès !');

            return $this->redirectToRoute('app_ville');
        }

        return $this->render('ville/new.html.twig', [
            'action' => 'add',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ville/del/{id}', name: 'app_ville_delete')]
    public function delete(VilleRepository $villeRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_VILLE_ADMIN');
        $ville = $villeRepository->findOneById($id);
        try{
        $entityManager->remove($ville);
        $entityManager->flush();
            $this->addFlash('success', 'Ville supprimée avec succès.');
        } catch (ForeignKeyConstraintViolationException $e) {
            // Ici, la suppression a échoué à cause des contraintes FK
            $this->addFlash('error', 'Impossible de supprimer la ville car elle possède des lieux rattachés.');
        }
        return $this->redirectToRoute('app_ville');
    }

    #[Route('/ville/edit/{id}', name: 'app_ville_edit')]
    public function edit(Request $request, VilleRepository $villeRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_VILLE_ADMIN');
        $ville = $villeRepository->findOneById($id);
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'Ville créée avec succès !');

            return $this->redirectToRoute('app_ville');
        }

        return $this->render('ville/new.html.twig', [
            'action' => 'edit',
            'form' => $form->createView(),
        ]);
        return $this->redirectToRoute('app_ville');
    }
}
