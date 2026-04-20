<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

       public function findByCategory($id = null): array
       {
           $qb =  $this->createQueryBuilder('i')
                ->leftJoin('i.categories','c')
                ->addSelect('c');
                if ($id != null) {
                    $qb
                    ->where('c.id = :id')
                    ->setParameter('id', $id);
                }
                        
            return $qb->getQuery()->getResult(); 
       }

        public function findByCategoryWithOffers($id = null): array
       {
           $qb =  $this->createQueryBuilder('i')
                ->leftJoin('i.categories','c')
                ->addSelect('c')
                ->leftJoin('i.offers','o')
                ->addSelect('o');
                if ($id != null) {
                    $qb
                    ->where('c.id = :id')
                    ->setParameter('id', $id);
                }
                        
            return $qb->getQuery()->getResult(); 
       }

        public function findItemWithOffers($id, $offer = null)
       {
           $qb =  $this->createQueryBuilder('i')
                ->leftJoin('i.offers','o')
                ->addSelect('o')
                ->where('o.item = :id')
                ->setParameter('id', $id);
                if ($offer != null) {
                    $qb  
                    ->leftJoin('o.user','u')
                    ->addSelect('u')
                    ->Where('u.id = o.user')
                    ;
                }

            return $qb->getQuery()->getOneOrNullResult(); 
       }

    //    public function findOneBySomeField($value): ?Item
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
