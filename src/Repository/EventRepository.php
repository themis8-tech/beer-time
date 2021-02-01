<?php
namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function search( $query, $sort )
    {
        $stmt = $this->createQueryBuilder('e');

        if( !empty( $query ) ){
            $stmt->leftJoin('e.categories', 'c');
            $stmt->leftJoin('e.place', 'p');

            $stmt->where('e.name LIKE :query');
            $stmt->orWhere('c.name LIKE :query');
            $stmt->orWhere('p.name LIKE :query');
            $stmt->orWhere('p.city LIKE :query');

            $stmt->setParameter('query', '%' . $query . '%');
        }

        switch ($sort){
            case 'startAt':
                $stmt->andWhere('e.endAt > CURRENT_TIMESTAMP()');
            case 'name':
                $stmt->orderBy('e.' . $sort, 'ASC');
                break;
            case 'createdAt':
            default:
                $stmt->orderBy('e.createdAt', 'DESC');
        }

        return $stmt->getQuery()->getResult();
    }
}
