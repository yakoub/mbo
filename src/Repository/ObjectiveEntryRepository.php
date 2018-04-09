<?php

namespace App\Repository;

use App\Entity\ObjectiveEntry;
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
    public function __construct(RegistryInterface $registry)
    {
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
        $builder->innerJoin('m.for_employee', 'e');
        $builder->addSelect('e');

        return $builder->getQuery()->getResult();
    }
}
