<?php

namespace App\Tests\Service\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Controller\CategoryService;
use App\Service\Controller\ProductService;
use App\Service\Controller\ViewProductService;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class ProductServiceTest
 * @package App\Tests\Service
 */
class ProductServiceTest extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $productService;

    private $productRepository;

    private $categoryRepository;

    private $viewProductService;

    public function setUp()
    {

        $this->productRepository = Mockery::mock(ProductRepository::class);
        $this->categoryRepository = Mockery::mock(CategoryRepository::class);
        $this->viewProductService = Mockery::mock(ViewProductService::class);

        $this->productService = new ProductService($this->productRepository, $this->categoryRepository, $this->viewProductService);
        parent::setUp();
    }

    public function testGetProducts(): void
    {
        $this->productRepository->shouldReceive('findAll')->once()->andReturnNull();

        $result = $this->productService->getProducts();
        $this->assertNotNull($result);
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        $this->productRepository->shouldReceive('findAll')->once()->andReturn(array(new Product()));

        $result = $this->productService->getProducts();

        $this->assertNotEmpty($result);
    }

    /**
     * @throws \Exception
     */
    public function testGetProductBySlug(): void
    {
        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getId')->once()->andReturn(1);
        $product->shouldReceive('getPrice')->once()->andReturn(1000);
        $product->shouldReceive('setPriceFloat')->once()->withArgs(array(12.3));

        $this->productRepository
            ->shouldReceive('getOneBySlug')
            ->withArgs(array('test'))
            ->once()
            ->andReturn($product);

        $this->viewProductService->shouldReceive('addView')->withArgs(array(1, 1))->once();

        $result = $this->productService->getProductBySlug('test',  1);

        $this->assertNotNull($result);
        $this->assertInstanceOf(Product::class, $result);
    }

    public function testGetProductList(): void
    {
        $persistentCollection = Mockery::mock(Collection::class);
        $persistentCollection->shouldReceive('getValues')->times(2)->andReturn(null, array(new Product()));

        $category = Mockery::mock(Category::class);
        $category
            ->shouldReceive('getProductReferences')
            ->times(2)
            ->andReturn($persistentCollection)
        ;

        $this->categoryRepository
            ->shouldReceive('getOneBySlug')
            ->times(2)
            ->withArgs(array('slugslug'))
            ->andReturn($category)
        ;

        $result = $this->productService->getProductInCategory('slug slug');
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        $result = $this->productService->getProductInCategory('slug slug');
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Product::class, $result[0]);
    }

    /**
     * @throws \Exception
     */
    public function testGetProduct(): void
    {
        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getPrice')->once()->andReturn(1000);
        $product->shouldReceive('setPriceFloat')->once()->withArgs(array(12.3));

        $this->productRepository
            ->shouldReceive('findOneById')
            ->withArgs(array(1))
            ->once()
            ->andReturn($product);

        $result = $this->productService->getProduct(1);

        $this->assertNotNull($result);
        $this->assertInstanceOf(Product::class, $result);

        $this->productRepository
            ->shouldReceive('findOneById')
            ->withArgs(array(1))
            ->once()
            ->andReturnNull();

        $result = $this->productService->getProduct(1, 'pl');

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
