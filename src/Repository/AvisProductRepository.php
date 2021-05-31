<?php

namespace App\Repository;

use App\Entity\AvisProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvisProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvisProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvisProduct[]    findAll()
 * @method AvisProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisProduct::class);
    }

    // /**
    //  * @return AvisProduct[] Returns an array of AvisProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AvisProduct
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
