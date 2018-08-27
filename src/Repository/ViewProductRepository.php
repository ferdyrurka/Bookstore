<?php


namespace App\Repository;

use App\Entity\ViewProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ViewProductRepository
 * @package App\Repository
 */
class ViewProductRepository extends ServiceEntityRepository
{
    /**
     * ViewProductRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewProduct::class);
    }

    /**
     * @param int $limit
     * @return array|null
     */
    public function findPopularProducts(int $limit): ?array
    {
        return $this->getEntityManager()->createQuery(
            'SELECT COUNT(p.productId), p.productId FROM App:ViewProduct p 
                  GROUP BY p.productId 
                  ORDER BY count(p.productId) DESC'
        )
            ->setMaxResults($limit)
            ->execute()
        ;
    }
}
