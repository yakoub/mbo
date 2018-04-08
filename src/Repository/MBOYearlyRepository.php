<?php

namespace App\Repository;

use App\Entity\MBOYearly;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MBOYearly|null find($id, $lockMode = null, $lockVersion = null)
 * @method MBOYearly|null findOneBy(array $criteria, array $orderBy = null)
 * @method MBOYearly[]    findAll()
 * @method MBOYearly[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MBOYearlyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MBOYearly::class);
    }

    public function aggregateYearForEmployee($year, $employee) {
        $builder = $this->createQueryBuilder('m');
        $builder->andWhere('m.year = :year');
        $builder->andWhere('m.for_employee = :employee');
        $builder->setParameter(':year', $year);
        $builder->setParameter(':employee', $employee);
        $builder->select('SUM(m.weight) as total_weight');
        
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
