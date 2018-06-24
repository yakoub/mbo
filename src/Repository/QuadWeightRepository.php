<?php

namespace App\Repository;

use App\Entity\QuadWeight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuadWeight|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuadWeight|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuadWeight[]    findAll()
 * @method QuadWeight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuadWeightRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuadWeight::class);
    }

//    /**
//     * @return QuadWeight[] Returns an array of QuadWeight objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuadWeight
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
