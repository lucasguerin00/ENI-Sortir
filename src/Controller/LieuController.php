<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Form\VilleType;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class LieuController extends AbstractController
{
    #[Route('/lieu', name: 'app_lieu')]
    #[IsGranted('ACCESS_LIEU', message: "Pas autorisé à regarder les lieux")]
    public function index(LieuRepository $lieuRepository): Response
    {
        $lieux = $lieuRepository->findAll();
        return $this->render('lieu/index.html.twig', [
            'lieux' => $lieux,
            'controller_name' => 'LieuController',
        ]);
    }

    #[Route('/lieu/add', name: 'app_lieu_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_LIEU_ADMIN');
        $lieu = new Lieu();

        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieu->setLongitude(0);
            $lieu->setLatitude(0);
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu créée avec succès !');

            return $this->redirectToRoute('app_lieu');
        }

        return $this->render('lieu/new.html.twig', [
            'action' => 'add',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/lieu/del/{id}', name: 'app_lieu_delete')]
    public function delete(LieuRepository $lieuRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $lieu = $lieuRepository->findOneById($id);
        $this->denyAccessUnlessGranted('ACCESS_LIEU_ADMIN', $lieu);
        $entityManager->remove($lieu);
        $entityManager->flush();
        return $this->redirectToRoute('app_lieu');
    }

    #[Route('/lieu/edit/{id}', name: 'app_lieu_edit')]
    public function edit(Request $request, LieuRepository $lieuRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $lieu = $lieuRepository->findOneById($id);
        $this->denyAccessUnlessGranted('ACCESS_LIEU_ADMIN', $lieu);
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu créé avec succès !');

            return $this->redirectToRoute('app_lieu');
        }

        return $this->render('lieu/new.html.twig', [
            'action' => 'edit',
            'form' => $form->createView(),
        ]);
        return $this->redirectToRoute('app_lieu');
    }
}
