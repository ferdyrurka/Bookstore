<?php


namespace App\Repository;

use App\Entity\Product;
use App\Exception\ProductNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ProductRepository
 * @package App\Repository
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * ProductRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string $slug
     * @return Product
     */
    public function getOneBySlug(string $slug): Product
    {
        $product = $this->findOneBy(array(
            'slug' => $slug
        ));

        if (!$product) {
            throw new ProductNotFoundException('Does product not found');
        }

        return $product;
    }

    /**
     * @param int $productId
     * @return Product
     */
    public function getOneById(int $productId): Product
    {
        $product = $this->find($productId);

        if (!$product) {
            throw new ProductNotFoundException('Does product not found');
        }

        return $product;
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function findByName(string $name): ?array
    {
        return $this->getEntityManager()->createQuery(
          'SELECT p FROM App:Product p WHERE p.name LIKE :name'
        )
            ->setParameter(':name', '%' . $name . '%')
            ->execute()
        ;
    }

    /**
     * @return array|null
     */
    public function findAll(): ?array
    {
        return parent::findAll();
    }

    /**
     * @param int $limit
     * @return array|null
     */
    public function findNewProducts(int $limit): ?array
    {
        return $this->getEntityManager()->createQuery('SELECT p FROM App:Product p ORDER BY p.createdAt DESC')
            ->setMaxResults($limit)
            ->execute()
        ;
    }

    /**
     * @param int $productId
     * @return Product
     */
    public function findOneById(int $productId): ?Product
    {
        return $this->find($productId);
    }
}
