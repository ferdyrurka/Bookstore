<?php

namespace App\Tests\Service\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Service\Controller\CategoryService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class CategoryServiceTest
 * @package App\Tests\Service
 */
class CategoryServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function setUp(): void
    {
        parent::setUp();
    }

    private $categoryRepository;
    private $categoryService;

    public function testGetCategories(): void
    {
        $this->categoryRepository = Mockery::mock(CategoryRepository::class);
        $this->categoryService = new CategoryService($this->categoryRepository);

        $this->categoryRepository->shouldReceive('findAll')->times(2)->andReturn(null, array(new Category()));

        $result = $this->categoryService->getCategories();
        $this->assertNotNull($result);
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));

        $result = $this->categoryService->getCategories();
        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
