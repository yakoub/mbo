<?php

namespace App\Repository;

use App\Entity\ObjectiveManagement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ObjectiveManagement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjectiveManagement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjectiveManagement[]    findAll()
 * @method ObjectiveManagement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectiveManagementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjectiveManagement::class);
    }
}
