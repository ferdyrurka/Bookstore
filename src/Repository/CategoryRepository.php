<?php


namespace App\Repository;

use App\Entity\Category;
use App\Exception\CategoryNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CategoryRepository
 * @package App\Repository
 */
class CategoryRepository extends ServiceEntityRepository
{

    /**
     * CategoryRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return array
     */
    public function findAll(): ?array
    {
        return parent::findAll();
    }

    /**
     * @param string $slug
     * @return Category
     */
    public function getOneBySlug(string $slug) :Category
    {
        $category = $this->findOneBy(array(
            'slug' => $slug
        ));

        if (!$category) {
            throw new CategoryNotFoundException('Does category not found');
        }

        return $category;
    }

    /**
     * @param int $categoryId
     * @return Category
     */
    public function getOneById(int $categoryId) :Category
    {
        $category = $this->find($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException('Does category not found');
        }

        return $category;
    }
}
