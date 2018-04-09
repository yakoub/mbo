<?php

namespace App\Repository;

use App\Entity\ObjectiveEntry;
use App\Repository\PersonRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ObjectiveEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjectiveEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjectiveEntry[]    findAll()
 * @method ObjectiveEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectiveEntryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry, PersonRepository $p_repository)
    {
        $this->person_repository = $p_repository;
        parent::__construct($registry, ObjectiveEntry::class);
    }

    public function aggregateYearForEmployee($year, $employee, $objective_entry = NULL) {
        $builder = $this->createQueryBuilder('m');
        $builder->andWhere('m.year = :year');
        $builder->andWhere('m.for_employee = :employee');
        $builder->setParameter(':year', $year);
        $builder->setParameter(':employee', $employee);
        $builder->select('SUM(m.weight) as total_weight');

        if ($objective_entry and $objective_entry->getId()) {
            $builder->andWhere('m != :objective_entry');
            $builder->setParameter(':objective_entry', $objective_entry);
        }
        
        return $builder->getQuery()->getSingleScalarResult();
    }

    public function getMyYear($year) {
        $builder = $this->createQueryBuilder('m');
        $builder->andWhere('m.year = :year');
        $builder->setParameter(':year', $year);
        $builder->groupBy('m.for_employee');
        $builder->select('sum(m.weight) as total_weight');
        $builder->addSelect('identity(m.for_employee) as employee');
        $groups = $builder->getQuery()->getResult();
        
        $weights = [];
        foreach ($groups as $mbo) {
            $weights[$mbo['employee']] = $mbo['total_weight'];
        }
        $employees = $this->person_repository->findBy(['id' => array_keys($weights)]);
        foreach ($employees as $employee) {
            $employee->total_weight = $weights[$employee->getId()];
        }

        return $employees;
    }
}
