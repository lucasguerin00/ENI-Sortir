<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
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
    private Security $security;

    public function __construct() {}

    #[Route('/profil', name: 'profil', methods: ['GET'])]
    public function showProfil(ParticipantRepository $participantRepository, SiteRepository $siteRepository): Response {

        $participant = $participantRepository->findOneById('1');

        return $this->render('profil/profil.html.twig', [
            'pseudo' => $participant->getPseudo(),
            'email' => $participant->getMail(),
            'nom' => $participant->getNom(),
            'prenom' => $participant->getPrenom(),
            'telephone' => $participant->getTelephone(),
            'site' => 'Nantes',
        ]);
    }

    #[Route('/profil/create', name: 'create_participant')]
    public function create(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = new Participant();

        $form = $this->createForm(ParticipantType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPassword()));

            // Enregistrer l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection ou message de succès
            return $this->redirectToRoute('profil');
        }

        return $this->render('profil/addProfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
