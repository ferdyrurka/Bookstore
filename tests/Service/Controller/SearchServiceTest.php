<?php

namespace App\Tests\Service\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Controller\SearchService;
use Symfony\Component\Form\Test\TypeTestCase;
use \Mockery;

/**
 * Class SearchServiceTest
 * @package App\Tests\Service
 */
class SearchServiceTest extends TypeTestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testSearchProducts(): void
    {
        $productRepository = Mockery::mock(ProductRepository::class);
        $productRepository
            ->shouldReceive('findByName')
            ->withArgs(array('test'))
            ->once()
            ->andReturnNull()
        ;

        $searchService = new SearchService($productRepository);

        $result = $searchService->searchProducts('test');
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        $productRepository
            ->shouldReceive('findByName')
            ->withArgs(array('test'))
            ->once()
            ->andReturn(array(new Product()))
        ;

        $result = $searchService->searchProducts('test');
        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
