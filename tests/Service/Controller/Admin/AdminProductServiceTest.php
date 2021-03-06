<?php

namespace App\Tests\Service\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Repository\ProductRepository;
use App\Request\CreateProductRequest;
use App\Request\SelectCategoriesRequest;
use App\Request\UpdateProductRequest;
use App\Request\UploadProductImageRequest;
use App\Service\Controller\Admin\AdminProductService;
use App\Service\UploadFile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AdminProductServiceTest
 * @package App\Tests\Service\Admin
 */
class AdminProductServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $productRepository;
    private $adminProductService;
    private $em;

    public function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->productRepository = Mockery::mock(ProductRepository::class);

        $this->adminProductService = new AdminProductService($this->productRepository, $this->em);
        parent::setUp();
    }

    public function testDeleteProduct(): void
    {
        $product = Mockery::mock(Product::class);
        $product->shouldReceive('setPriceFloat');
        $product->shouldReceive('getPrice')->andReturn(10);
        $product->shouldReceive('getProductImageReferences')->times(2)->andReturn(null, new ProductImage());

        $this->productRepository->shouldReceive('getOneById')->withArgs(array(3))->times(2)->andReturn($product);

        $this->em->shouldReceive('remove')->withArgs(array(Product::class))->once();
        $this->em->shouldReceive('flush')->times(2);

        $this->assertNull($this->adminProductService->deleteProduct(3));

        $this->em->shouldReceive('remove')->with(
            Mockery::on(function ($obj) {
                if ($obj instanceof Product || $obj instanceof ProductImage) {
                    return true;
                }

                return false;
            })
        )->times(2);

        $this->assertNull($this->adminProductService->deleteProduct(3));
    }

    public function testCreateProduct(): void
    {
        $uploadedFile = Mockery::mock(UploadedFile::class);

        $uploadProductImage = Mockery::mock(UploadProductImageRequest::class);
        $uploadProductImage->shouldReceive('getProductImage')->times(2)->andReturn(null, $uploadedFile);

        $product = Mockery::mock(Product::class);
        $product->shouldReceive('setCategoryReferences')->times(2)->withArgs(array(ArrayCollection::class));
        $product->shouldReceive('setProductImageReferences')->zeroOrMoreTimes()->withArgs(array(ProductImage::class));
        $product->shouldReceive('setPrice')->withArgs(array(1000))->times(2);
        $product->shouldReceive('setCreatedAt')->times(2)->withArgs(array(\DateTime::class));
        $product->shouldReceive('getPriceFloat')->times(2)->andReturn(10);
        $product->shouldReceive('getUploadProductImage')->times(2)->andReturn(array($uploadProductImage));
        $product->shouldReceive('getCategoriesId')->times(2)->andReturn(new ArrayCollection());

        $this->em->shouldReceive('persist')->withArgs(array(Product::class))->once();
        $this->em->shouldReceive('flush')->times(2);

        $uploadFile = Mockery::mock(UploadFile::class);
        $uploadFile->shouldReceive('upload')->andReturn(new ProductImage())->once();

        $this->assertNull($this->adminProductService->createProduct($product, $uploadFile));

        /**
         * Tests added a product image
         */

        $this->em
            ->shouldReceive('persist')
            ->with(
                Mockery::on(function ($obj) {
                    if ($obj instanceof Product || $obj instanceof ProductImage) {
                        return true;
                    }

                    return false;
                })
            )
            ->times(2);

        $this->assertNull($this->adminProductService->createProduct($product, $uploadFile));
    }

    public function testGetProduct(): void
    {
        $product = new Product();
        $product->setPrice(1000);

        $this->productRepository->shouldReceive('getOneById')->once()->withArgs(array(1))->andReturn($product);

        $this->assertInstanceOf(Product::class, $this->adminProductService->getProduct(1));
    }

    public function testUpdateProduct(): void
    {
        $uploadedFile = Mockery::mock(UploadedFile::class);

        $uploadProductImage = Mockery::mock(UploadProductImageRequest::class);
        $uploadProductImage->shouldReceive('getProductImage')->times(2)->andReturn(null, $uploadedFile);

        $product = Mockery::mock(Product::class);
        $product->shouldReceive('setCategoryReferences')->times(2)->withArgs(array(ArrayCollection::class));
        $product->shouldReceive('setProductImageReferences')->zeroOrMoreTimes()->withArgs(array(ProductImage::class));
        $product->shouldReceive('setPrice')->withArgs(array(1000))->times(2);
        $product->shouldReceive('setCreatedAt')->never();
        $product->shouldReceive('getPriceFloat')->times(2)->andReturn(10);
        $product->shouldReceive('getUploadProductImage')->times(2)->andReturn(array($uploadProductImage));
        $product->shouldReceive('getCategoriesId')->times(2)->andReturn(new ArrayCollection());

        $this->em->shouldReceive('persist')->withArgs(array(Product::class))->once();
        $this->em->shouldReceive('flush')->times(2);

        $uploadFile = Mockery::mock(UploadFile::class);
        $uploadFile->shouldReceive('upload')->andReturn(new ProductImage())->once();

        $this->assertNull($this->adminProductService->updateProduct($product, $uploadFile));

        /**
         * Tests added a product image
         */

        $this->em
            ->shouldReceive('persist')
            ->with(
                Mockery::on(function ($obj) {
                    if ($obj instanceof Product || $obj instanceof ProductImage) {
                        return true;
                    }

                    return false;
                })
            )
            ->times(2);

        $this->assertNull($this->adminProductService->updateProduct($product, $uploadFile));
    }

    public function testGetAll(): void
    {
        $this->productRepository->shouldReceive('findAll')->times(2)->andReturn(null, array(new Product()));

        $result = $this->adminProductService->getAll();
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        $result = $this->adminProductService->getAll();
        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
