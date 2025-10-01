<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ParticipantRepository $participantRepository): Response
    {
        $utilisateurs = $participantRepository->findAll();
        $this->denyAccessUnlessGranted('ACCESS_ADMIN');
        return $this->render('admin/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/del/{id}', name: 'app_admin_delete')]
    public function delUser(ParticipantRepository $participantRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_ADMIN');
        $utilisateur = $participantRepository->findOneById($id);
        $entityManager->remove($utilisateur);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/disable/{id}', name: 'app_admin_disable')]
    public function disableUser(ParticipantRepository $participantRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_ADMIN');
        $utilisateur = $participantRepository->findOneById($id);
        $utilisateur->setIsActif(0);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/enable/{id}', name: 'app_admin_enable')]
    public function enableUser(ParticipantRepository $participantRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_ADMIN');
        $utilisateur = $participantRepository->findOneById($id);
        $utilisateur->setIsActif(1);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }


}
