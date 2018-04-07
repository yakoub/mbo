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

//    /**
//     * @return MBOYearly[] Returns an array of MBOYearly objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MBOYearly
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
