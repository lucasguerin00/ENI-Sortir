<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\CsvUploadType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ParticipantRepository $participantRepository, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $utilisateurs = $participantRepository->findAll();
        $form = $this->uploadCsv($request, $em, $passwordEncoder);
        return $this->render('admin/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
        ]);

    }
    #[Route('/admin/del/{id}', name: 'app_admin_delete')]
    public function delUser(ParticipantRepository $participantRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $participantRepository->findOneById($id);
        $entityManager->remove($utilisateur);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/disable/{id}', name: 'app_admin_disable')]
    public function disableUser(ParticipantRepository $participantRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $participantRepository->findOneById($id);
        $utilisateur->setIsActif(0);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/enable/{id}', name: 'app_admin_enable')]
    public function enableUser(ParticipantRepository $participantRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $participantRepository->findOneById($id);
        $utilisateur->setIsActif(1);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

    public function uploadCsv(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder): ?FormInterface
    {

        $form = $this->createForm(CsvUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('csv_file')->getData();
            dump($file);

            if($file) {

                $handle = fopen($file->getPathname(), 'r');
                $headers = fgetcsv($handle, 1000, ';');

                while(($data = fgetcsv($handle, 1000, ';')) != false) {
                    $user = new Participant();
                    $user->setNom($data[0]);
                    $user->setPrenom($data[1]);
                    $user->setPseudo($data[2]);
                    $user->setMail($data[3]);
                    $user->setPassword($passwordEncoder->hashPassword($user, $data[4]));
                    $user->setTelephone($data[5]);
                    $em->persist($user);
                }
                fclose($handle);
                $em->flush();

                $this->addFlash('success', 'CSV importé avec succès !');

            }
        }

        return $form;
    }


}
