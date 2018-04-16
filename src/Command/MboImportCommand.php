<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Person;

class MboImportCommand extends Command
{
    protected static $defaultName = 'mbo:import';

    protected function configure()
    {
        $this
            ->setDescription('import users over ldap')
        ;
    }

    protected $manager;

    public function __construct(ObjectManager $manager) {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $p = new Person();
        $p->setName("console person");
        $p->setEmail("cp@dd.d");
        $this->manager->persist($p);
        $this->manager->flush();

        $output->writeln('p id is=' . $p->getId());
    }
}
