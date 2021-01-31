<?php

namespace App\Repository;

use App\Entity\CollectionOuvrage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CollectionOuvrage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CollectionOuvrage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CollectionOuvrage[]    findAll()
 * @method CollectionOuvrage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionOuvrageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CollectionOuvrage::class);
    }

    // /**
    //  * @return CollectionOuvrage[] Returns an array of CollectionOuvrage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CollectionOuvrage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
