<?php

namespace App\Tests\Service\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Controller\ProductService;
use App\Service\Controller\ViewProductService;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class ProductServiceTest
 * @package App\Tests\Service
 */
class ProductServiceTest extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testGetProducts(): void
    {
        $productRepository = Mockery::mock(ProductRepository::class);

        $productRepository->shouldReceive('findAll')->once()->andReturnNull();

        $productService = new ProductService($productRepository);

        $result = $productService->getProducts();
        $this->assertNotNull($result);
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        $productRepository->shouldReceive('findAll')->once()->andReturn(array(new Product()));

        $result = $productService->getProducts();

        $this->assertNotEmpty($result);
    }

    /**
     * @throws \Exception
     */
    public function testGetProductBySlug(): void
    {
        $productRepository = Mockery::mock(ProductRepository::class);

        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getId')->once()->andReturn(1);
        $product->shouldReceive('getPrice')->once()->andReturn(1000);
        $product->shouldReceive('setPriceFloat')->once()->withArgs(array(12.3));

        $productRepository
            ->shouldReceive('getOneBySlug')
            ->withArgs(array('test'))
            ->once()
            ->andReturn($product);

        $viewProductRepository = Mockery::mock(ViewProductService::class);
        $viewProductRepository->shouldReceive('addView')->withArgs(array(1, 1))->once();

        $productService = new ProductService($productRepository);
        $result = $productService->getProductBySlug('test', 'pl', $viewProductRepository, 1);

        $this->assertNotNull($result);
        $this->assertInstanceOf(Product::class, $result);
    }

    /**
     * @throws \Exception
     */
    public function testGetProduct(): void
    {
        $productRepository = Mockery::mock(ProductRepository::class);

        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getPrice')->once()->andReturn(1000);
        $product->shouldReceive('setPriceFloat')->once()->withArgs(array(12.3));

        $productRepository
            ->shouldReceive('findOneById')
            ->withArgs(array(1))
            ->once()
            ->andReturn($product);

        $productService = new ProductService($productRepository);
        $result = $productService->getProduct(1, 'pl');

        $this->assertNotNull($result);
        $this->assertInstanceOf(Product::class, $result);

        $productRepository
            ->shouldReceive('findOneById')
            ->withArgs(array(1))
            ->once()
            ->andReturnNull();

        $result = $productService->getProduct(1, 'pl');

        $this->assertNotNull($result);
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Unknown', $result->getName());
        $this->assertEquals(0, $result->getPriceFloat());
        $this->assertEquals(0, $result->getMagazine());
        $this->assertEquals('#', $result->getSlug());
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
