<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    private Security $security;

    public function __construct() {}

    #[Route('/profil', name: 'profil', methods: ['GET'])]
    public function showProfil(ParticipantRepository $participantRepository, SiteRepository $siteRepository): Response {

        $participant = $participantRepository->find($this->security->getUser()->getId());

        return $this->render('main/profil.html.twig', [
            'pseudo' => $participant['pseudo'],
            'email' => $participant['email'],
            'nom' => $participant['nom'],
            'prenom' => $participant['prenom'],
            'telephone' => $participant['telephone'],
            'site' => $siteRepository->find($participant['id_site_id'])
        ]);
    }

    #[Route('/profil/create', name: 'create_participant')]
    public function create(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Participant();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            // Enregistrer l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection ou message de succès
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
