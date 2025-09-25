<?php

namespace App\Command;

use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'sortie:cleanup')]
class CleanupSortieCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = new \DateTime('-1 minute'); // TODO, faire attention à la limite de suppression des archives

        $qb = $this->em->createQueryBuilder();
        $qb->delete(Sortie::class, 's')
            ->where('s.isArchived = true')
            ->andWhere('s.archivedAt <= :limit')
            ->setParameter('limit', $limit)
            ->getQuery()
            ->execute();

        $output->writeln('Sorties archivées il y a plus de 24h supprimées.');
        return Command::SUCCESS;
    }
}
