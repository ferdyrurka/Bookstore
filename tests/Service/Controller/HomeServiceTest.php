<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\ViewProductRepository;
use App\Service\Controller\HomeService;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class HomeServiceTest
 * @package App\Tests\Service
 */
class HomeServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $productRepository;
    private $viewProductRepository;
    private $homeService;

    public function setUp(): void
    {
        $this->productRepository = Mockery::mock(ProductRepository::class);
        $this->viewProductRepository = Mockery::mock(ViewProductRepository::class);
        $this->homeService = new HomeService($this->productRepository, $this->viewProductRepository);

        parent::setUp();
    }

    public function testGetNewProducts(): void
    {
        $this->productRepository
            ->shouldReceive('findNewProducts')
            ->times(2)
            ->withArgs(array(6))
            ->andReturn(null, array(new Product()));

        $result = $this->homeService->getNewProducts(6);

        $this->assertNotNull($result);
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        $result = $this->homeService->getNewProducts(6);

        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
    }

    public function testGetPopularProducts(): void
    {
        $this->viewProductRepository
            ->shouldReceive('findPopularProducts')
            ->with(6)
            ->once()
            ->andReturn(array(
                1 => array('productId' => 1),
            ))
        ;

        $this->productRepository
            ->shouldReceive('findOneById')
            ->once()
            ->withArgs(array(1))
            ->andReturnNull()
        ;

        $result = $this->homeService->getPopularProducts(6);
        $this->assertEquals('Unknown', $result[0]->getName());
        $this->assertEquals(0, $result[0]->getPriceFloat());
        $this->assertEquals(0, $result[0]->getMagazine());

        $this->viewProductRepository
            ->shouldReceive('findPopularProducts')
            ->with(6)
            ->once()
            ->andReturn(array(
                1 => array('productId' => 1),
            ))
        ;

        $product = new Product();
        $product->setName('Product database');

        $this->productRepository
            ->shouldReceive('findOneById')
            ->once()
            ->withArgs(array(1))
            ->andReturn($product)
        ;

        $result = $this->homeService->getPopularProducts(6);
        $this->assertEquals('Product database', $result[0]->getName());

        $this->viewProductRepository
            ->shouldReceive('findPopularProducts')
            ->with(6)
            ->once()
            ->andReturnNull()
        ;

        $result = $this->homeService->getPopularProducts(6);
        $this->assertNotNull($result);
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
