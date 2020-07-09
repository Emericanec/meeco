<?php

namespace App\Repository;

use App\Entity\CtlgAccountStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CtlgAccountStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtlgAccountStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtlgAccountStatus[]    findAll()
 * @method CtlgAccountStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtlgAccountStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtlgAccountStatus::class);
    }

    // /**
    //  * @return CtlgAccountStatus[] Returns an array of CtlgAccountStatus objects
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
    public function findOneBySomeField($value): ?CtlgAccountStatus
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
