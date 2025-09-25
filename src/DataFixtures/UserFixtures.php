<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $userAdmin = new \App\Entity\Participant();
        $userAdmin->setMail('chaigneau851@gmail.com');
        $userAdmin->setNom('Chaigneau');
        $userAdmin->setPrenom('Aimé');
        $userAdmin->setPseudo('AragornDu85');
        $userAdmin->setIdSite(null);
        $userAdmin->setTelephone("025103504");
        $userAdmin->setIsAdministrateur(1);
        $userAdmin->setIsActif(1);
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $plaintextPassword = "1234";

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $userAdmin,
            $plaintextPassword
        );
        $userAdmin->setPassword($hashedPassword);
        $manager->persist($userAdmin);

        $userCloe = new \App\Entity\Participant();
        $userCloe->setMail('cloé@gmail.com');
        $userCloe->setNom('Lemaréchal');
        $userCloe->setPrenom('Cloé');
        $userCloe->setPseudo('Cloé27');
        $userCloe->setIdSite(null);
        $userCloe->setTelephone("025103504");
        $userCloe->setIsAdministrateur(1);
        $userCloe->setIsActif(1);
        $userCloe->setRoles(['ROLE_ADMIN']);
        $plaintextPassword = "1234";

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $userCloe,
            $plaintextPassword
        );
        $userCloe->setPassword($hashedPassword);
        $manager->persist($userCloe);

        $userLucas = new \App\Entity\Participant();
        $userLucas->setMail('lucas@gmail.com');
        $userLucas->setNom('Guérin');
        $userLucas->setPrenom('Lucas');
        $userLucas->setPseudo('lucasguerin00');
        $userLucas->setIdSite(null);
        $userLucas->setTelephone("025103504");
        $userLucas->setIsAdministrateur(1);
        $userLucas->setIsActif(1);
        $userLucas->setRoles(['ROLE_ADMIN']);
        $plaintextPassword = "1234";

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $userLucas,
            $plaintextPassword
        );
        $userLucas->setPassword($hashedPassword);
        $manager->persist($userLucas);

        $manager->flush();
    }
}
