<?php

namespace App\Repository;

use App\Entity\FactureArticle;
use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method FactureArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureArticle[]    findAll()
 * @method FactureArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureArticle::class);
    }


    public function nombreligne()
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('COUNT(a.id) AS nombre');
            //->where('a.actif = true')
          //  ->orderBy('a.datePublication', 'DESC');

        return $qb->getQuery()->getSingleScalarResult();
    }


    public function totalParFacture( Facture $facture)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('SUM(a.total) AS total')
             ->where('a.factures = :factures')
                ->setParameter('factures', $facture)
        ;
        //  ->orderBy('a.datePublication', 'DESC');

        return $qb->getQuery()->getSingleScalarResult();
    }


    public function sommeTotal()
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('SUM(a.total) AS total')
            // ->where('a.factures = true')
        ;
        //  ->orderBy('a.datePublication', 'DESC');

        return $qb->getQuery()->getSingleScalarResult();
    }



    // /**
    //  * @return FactureArticle[] Returns an array of FactureArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FactureArticle
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
