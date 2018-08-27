<?php

namespace App\Tests\Service\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\Controller\Admin\AdminOrderService;
use \Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Class AdminOrderServiceTest
 * @package App\Tests\Service\Controller\Admin
 */
class AdminOrderServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testGetAll(): void
    {
        $orderRepository = Mockery::mock(OrderRepository::class);
        $orderRepository->shouldReceive('findAll')->times(2)->andReturn(null, array(new Order()));

        $adminOrderService = new AdminOrderService($orderRepository);
        $result = $adminOrderService->getAll();
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        $result = $adminOrderService->getAll();
        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
    }
}
