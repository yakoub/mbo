<?php

namespace App\Repository;

use App\Entity\ObjectiveManagement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ObjectiveManagement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjectiveManagement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjectiveManagement[]    findAll()
 * @method ObjectiveManagement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectiveManagementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ObjectiveManagement::class);
    }

//    /**
//     * @return ObjectiveManagement[] Returns an array of ObjectiveManagement objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ObjectiveManagement
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
