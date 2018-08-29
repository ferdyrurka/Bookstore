<?php


namespace App\Service\Controller;

use App\Repository\CategoryRepository;

/**
 * Class CategoryService
 * @package App\Service\Controller
 */
class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        $category = $this->categoryRepository->findAll();

        if (is_null($category)) {
            return array();
        }

        return $category;
    }
}
