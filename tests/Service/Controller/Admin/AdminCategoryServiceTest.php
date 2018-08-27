<?php

namespace App\Tests\Service\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Request\CategoryRequest;
use App\Service\Controller\Admin\AdminCategoryService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class AdminCategoryServiceTest
 * @package App\Tests\Service\Admin
 */
class AdminCategoryServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $em;
    private $categoryRepository;
    private $adminCategoryService;

    public function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->categoryRepository = Mockery::mock(CategoryRepository::class);
        $this->adminCategoryService = new AdminCategoryService($this->em, $this->categoryRepository);
        parent::setUp();
    }

    public function testDeleteCategory(): void
    {
        $this->categoryRepository->shouldReceive('getOneById')->withArgs(array(1))->once()->andReturn(new Category());

        $this->em->shouldReceive('remove')->once()->withArgs(array(Category::class));
        $this->em->shouldReceive('flush')->once();

        $this->assertNull($this->adminCategoryService->deleteCategory(1));
    }

    public function testCreateCategory(): void
    {
        $categoryRequest = Mockery::mock(CategoryRequest::class);
        $categoryRequest->shouldReceive('getName')->andReturn('name')->once();
        $categoryRequest->shouldReceive('getDescription')->andReturn('description')->once();

        $this->em->shouldReceive('persist')->withArgs(array(Category::class))->once();
        $this->em->shouldReceive('flush')->once();

        $this->assertNull($this->adminCategoryService->createCategory($categoryRequest));
    }

    public function testGetCategory(): void
    {
        $this->categoryRepository->shouldReceive('getOneById')->withArgs(array(2))->once()->andReturn(new Category());
        $this->assertInstanceOf(Category::class, $this->adminCategoryService->getCategory(2));
    }

    public function testGetAll(): void
    {
        $this->categoryRepository->shouldReceive('findAll')->once()->andReturnNull();
        $result = $this->adminCategoryService->getAll();
        $this->assertEmpty($result);
        $this->assertTrue(is_array($result));
    }

    public function testUpdateCategory(): void
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('setName')->withArgs(array('name'))->once();
        $category->shouldReceive('setDescription')->withArgs(array('description'))->once();

        $categoryRequest = Mockery::mock(CategoryRequest::class);
        $categoryRequest->shouldReceive('getName')->andReturn('name')->once();
        $categoryRequest->shouldReceive('getDescription')->andReturn('description')->once();

        $this->em->shouldReceive('persist')->withArgs(array(Category::class))->once();
        $this->em->shouldReceive('flush')->once();

        $this->assertNull($this->adminCategoryService->updateCategory($categoryRequest, $category));
    }

    public function tearDown(): void
    {
        Mockery::class;
        parent::tearDown();
    }
}
