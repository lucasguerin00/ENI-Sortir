<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Form\ChangePasswordType;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    public function __construct() {}

    #[Route('/profil/{id}', name: 'profil', methods: ['GET'])]
    public function showProfil(int $id, ParticipantRepository $participantRepository, SiteRepository $siteRepository): Response {

        $participant = $participantRepository->findOneById($id);
        $site = $siteRepository->findOneBy(['id' => $participant->getIdSite()]);

        return $this->render('profil/profil.html.twig', [
            'id' => $participant->getId(),
            'pseudo' => $participant->getPseudo(),
            'email' => $participant->getMail(),
            'nom' => $participant->getNom(),
            'prenom' => $participant->getPrenom(),
            'telephone' => $participant->getTelephone(),
            'site' => $site->getNom(),
            'image' => $participant->getImage(),
        ]);
    }

    #[Route('/create', name: 'create_participant')]
    public function createParticipant(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = new Participant();

        $form = $this->createForm(ParticipantType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('image')->getData()) {

                $originalFilename = $form->get('image')->getData()->getClientOriginalName();

                // Déplacer le fichier dans /public/uploads pour qu'il soit accessible
                $form->get('image')->getData()->move(
                    $this->getParameter('images_directory'),
                    $originalFilename
                );

                $user->setImage($originalFilename);
            }

            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('profil/addProfil.html.twig', [
            'form' => $form->createView(),
            'editMode' => false,
        ]);
    }

    #[Route('/profil/{id}/edit', name: 'edit_participant')]
    public function editParticipant(Participant $participant, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('password')->getData()!==null){
                $participant->setPassword($passwordEncoder->hashPassword($participant, $form->get('password')->getData()));
            }
            if ($form->get('image')->getData()) {

                $originalFilename = $form->get('image')->getData()->getClientOriginalName();

                // Déplacer le fichier dans /public/uploads pour qu'il soit accessible
                $form->get('image')->getData()->move(
                    $this->getParameter('images_directory'),
                    $originalFilename
                );

                $participant->setImage($originalFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('profil', ['id' => $participant->getId()]);
        }

        return $this->render('profil/addProfil.html.twig', [
            'form' => $form->createView(),
            'editMode' => true
        ]);
    }

    #[Route('/password', name: 'change_password')]
    public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder, ParticipantRepository $participantRepository): Response
    {

        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('mail')->getData();
            $user = $participantRepository->findOneByMail($email);

            if($user !== null){

                $newPassword = $passwordEncoder->hashPassword($user, $form->get('password')->getData());

                $user->setPassword($newPassword);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès.');

                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
                return $this->redirectToRoute('change_password');
            }
        }

        return $this->render('profil/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
