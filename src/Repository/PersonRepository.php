<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Person[]    getRoot()
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function getRoot()
    {

        $queryBuilder = $this->createQueryBuilder('p');
        $query = $queryBuilder->innerJoin('p.employees', 'e', 'WITH', 'p.manager IS NULL')
            ->addSelect('e')
            ->getQuery();
        return $query->getResult();
    }

    public function getTree(Person $person) 
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query = $queryBuilder->innerJoin('p.manager', 'e', 'WITH', 'p.manager = :id')
            ->setParameter('id', $person->getId())
            ->addSelect('e')
            ->getQuery();
        return $query->getResult();
    }
}
