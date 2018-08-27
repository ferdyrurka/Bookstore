<?php

namespace App\Tests\Service\Controller;

use App\Entity\Product;
use App\Entity\ViewProduct;
use App\Repository\ViewProductRepository;
use App\Service\Controller\ViewProductService;
use App\Service\UserAgent;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class ViewProductServiceTest
 * @package App\Tests\Service
 */
class ViewProductServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @throws \Exception
     */
    public function testAddView(): void
    {
        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('persist')->withArgs(array(ViewProduct::class))->once();
        $em->shouldReceive('flush')->once();
        $userAgent = Mockery::mock(UserAgent::class);
        $userAgent->shouldReceive('getIp')->andReturn('192.168.1.1')->once();
        $userAgent->shouldReceive('getUserAgent')->andReturn('Windows 10')->once();

        $viewProducts = new ViewProductService($em, $userAgent);

        $this->assertNull($viewProducts->addView(1, 1));
    }

    public function testGetPopularProducts(): void
    {
        $em = Mockery::mock(EntityManagerInterface::class);
        $userAgent = Mockery::mock(UserAgent::class);
        $viewProducts = new ViewProductService($em, $userAgent);

        $viewProductsRepository = Mockery::mock(ViewProductRepository::class);
        $viewProductsRepository
            ->shouldReceive('findPopularProducts')
            ->withArgs(array(6))
            ->once()
            ->andReturn(array(new Product()))
        ;

        $this->assertNotEmpty($viewProducts->getPopularProducts(6, $viewProductsRepository));

        $viewProductsRepository
            ->shouldReceive('findPopularProducts')
            ->withArgs(array(6))
            ->once()
            ->andReturn(null)
        ;

        $this->assertEmpty($viewProducts->getPopularProducts(6, $viewProductsRepository));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
