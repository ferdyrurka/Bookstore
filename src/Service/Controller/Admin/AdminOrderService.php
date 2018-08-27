<?php


namespace App\Service\Controller\Admin;

use App\Repository\OrderRepository;

/**
 * Class AdminOrderService
 * @package App\Service\Controller\Admin
 */
class AdminOrderService
{
    private $orderRepository;

    /**
     * AdminOrderService constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAll(): array
    {
        $orders = $this->orderRepository->findAll();

        if (is_null($orders)) {
            return array();
        }

        return $orders;
    }
}
