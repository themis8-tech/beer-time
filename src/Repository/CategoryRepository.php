<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findActiveCategory()
    {
        //SELECT category.name FROM category
        //WHERE category.name like %d%
        //LIMIT 2

        //c = alias choisi pour category
        //creation d'une requete
        $stmt=$this->createQueryBuilder('c');
        //SELECT category.name
        $stmt->select('c.name');
        //pas besoin du FROM : on est deja dans CategoryRepository
        //Where category.name (search = type du form)
        $stmt->where('c.name LIKE:search');
        //like %d%
        $stmt->setParameter('search', '%d%');
        //limit 2
        $stmt->setMaxResults(2);

        // return le resultat
        return $stmt ->getQuery()->getResult();
    }

    // public function findWhithoutIcon()
    // {
    //     $stmt = $this->createQueryBuilder('c');
    //     $stmt->where('c.icon IS NULL');
    //     return $stmt->GetQuery()->getResult();
    // }

    // /**
    //  * @return Category[] Returns an array of Category objects
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
    public function findOneBySomeField($value): ?Category
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
