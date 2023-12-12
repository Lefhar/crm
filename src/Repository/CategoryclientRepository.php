<?php

namespace App\Repository;

use App\Entity\Categoryclient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categoryclient>
 *
 * @method Categoryclient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoryclient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoryclient[]    findAll()
 * @method Categoryclient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryclientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoryclient::class);
    }

//    /**
//     * @return Categoryclient[] Returns an array of Categoryclient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Categoryclient
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
