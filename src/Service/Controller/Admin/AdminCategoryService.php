<?php


namespace App\Service\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Request\CategoryRequest;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AdminCategoryService
 * @package App\Service\Controller\Admin
 */
class AdminCategoryService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * AdminCategoryService constructor.
     * @param EntityManagerInterface $em
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CategoryRequest $categoryRequest
     */
    public function createCategory(CategoryRequest $categoryRequest): void
    {
        $category = new Category();
        $category->setName($categoryRequest->getName());
        $category->setDescription($categoryRequest->getDescription());

        $this->em->persist($category);
        $this->em->flush();
    }

    /**
     * @param CategoryRequest $categoryRequest
     * @param Category $category
     */
    public function updateCategory(CategoryRequest $categoryRequest, Category $category): void
    {
        $category->setName($categoryRequest->getName());
        $category->setDescription($categoryRequest->getDescription());

        $this->em->persist($category);
        $this->em->flush();
    }

    /**
     * @param int $categoryId
     * @return Category
     */
    public function getCategory(int $categoryId): Category
    {
        return $this->categoryRepository->getOneById($categoryId);
    }

    /**
     * @param int $categoryId
     */
    public function deleteCategory(int $categoryId): void
    {
        $this->em->remove($this->getCategory($categoryId));
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $categories = $this->categoryRepository->findAll();

        if (!$categories) {
            return array();
        }

        return $categories;
    }
}
