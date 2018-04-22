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
use App\Repository\PersonRepository;

class MboImportCommand extends Command
{
    use UserImportTrait;

    protected static $defaultName = 'mbo:import';

    protected function configure()
    {
        $this
            ->setDescription('import users over ldap')
        ;
    }

    protected $manager;
    protected $repository;
    protected $output;

    public function __construct(ObjectManager $manager, PersonRepository $repository) {
        $this->manager = $manager;
        $this->repository = $repository;
        parent::__construct();
    }

    protected function getManager() {
        return $this->manager;
    }
    protected function getRepository() {
        return $this->repository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conn = $this->ldap();
        if (!$conn) {
            throw new \Exception('Connection failed');
        }
        $this->output = $output;
        list($base_dn, $attr, $filter) = $this->search_parameters();
        $cookie = '';
        $page_size = 500;
        $total = 0;
        do {
            ldap_control_paged_result($conn, $page_size, true, $cookie);
            $resource = ldap_search($conn, $base_dn, $filter, $attr);
            if ($resource) {
              $entries = ldap_get_entries($conn, $resource);
              unset($entries['count']);
              foreach ($entries as $entry) {
                try {
                  $this->saveUser($entry);
                }
                catch (\PDOException $e) { // doens't work
                }
              }
              $output->writeln('new page');
              $this->getManager()->flush();
              ldap_control_paged_result_response($conn, $resource, $cookie);
            }
            else {
              $cookie = null;
            }
        } while ($cookie !== null and $cookie != '');
    }
}
