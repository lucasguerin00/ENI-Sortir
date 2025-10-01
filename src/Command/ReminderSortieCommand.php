<?php

namespace App\Command;

use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;

#[AsCommand(name: 'sortie:reminder',)]
class ReminderSortieCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTime();
        $tomorrow = (clone $now)->modify('+1 day');

        $repo = $this->entityManager->getRepository(Sortie::class);

        // Récupère les sorties dont le début est dans les 24 prochaines heures
        $sorties = $repo->createQueryBuilder('s')
            ->where('s.dateHeureDebut BETWEEN :now AND :tomorrow')
            ->setParameter('now', $now)
            ->setParameter('tomorrow', $tomorrow)
            ->getQuery()
            ->getResult();

        foreach ($sorties as $sortie) {
            foreach ($sortie->getParticipants() as $participant) {
                $email = (new TemplatedEmail())
                    ->from('no-reply@sortir.com')
                    ->to($participant->getMail())
                    ->subject('Rappel : la sortie "'.$sortie->getNom().'" est dans 24h !')
                    ->htmlTemplate('emails/reminder.html.twig')
                    ->context([
                        'participant' => $participant,
                        'sortie' => $sortie,
                    ]);

                $this->mailer->send($email);

                $output->writeln("Mail envoyé à ".$participant->getMail()." pour sortie ".$sortie->getNom());
            }
        }

        return Command::SUCCESS;
    }
}
