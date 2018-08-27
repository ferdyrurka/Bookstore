<?php


namespace App\Repository;

use App\Entity\Order;
use App\Exception\OrderNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class OrderRepository
 * @package App\Repository
 */
class OrderRepository extends ServiceEntityRepository
{

    /**
     * ProductRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param int $userId
     * @return Order|null
     */
    public function findOneByUserId(int $userId): ?Order
    {
        return $this->findOneBy(array('userId' => $userId));
    }

    /**
     * @param string $sort
     * @return array|null
     * @throws \Exception
     */
    public function findAll($sort = 'DESC'): ?array
    {
        if($sort != 'DESC' && $sort != 'ASC') {
            throw new \Exception('This sorted is unknown');
        }

        return $this->getEntityManager()->createQuery('
            SELECT p FROM App:Order p ORDER BY p.id '.$sort)->getResult();
    }

    /**
     * @param int $orderId
     * @return Order
     */
    public function getOnyById(int $orderId): Order
    {
        $order =  $this->find($orderId);

        if (!$order) {
            throw new OrderNotFoundException('Order not found');
        }

        return $order;
    }
}
