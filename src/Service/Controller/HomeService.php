<?php


namespace App\Service\Controller;

use App\NullObject\Repository\NullProductRepository;
use App\Repository\ProductRepository;
use App\Repository\ViewProductRepository;

/**
 * Class HomeService
 * @package App\Service\Controller
 */
class HomeService
{

    /**
     * @var ViewProductRepository
     */
    private $viewProductRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * HomeService constructor.
     * @param ProductRepository $productRepository
     * @param ViewProductRepository $viewProductRepository
     */
    public function __construct(ProductRepository $productRepository, ViewProductRepository $viewProductRepository)
    {
        $this->productRepository = $productRepository;
        $this->viewProductRepository = $viewProductRepository;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getNewProducts(int $limit): array
    {
        $newProduct = $this->productRepository->findNewProducts($limit);

        if (is_null($newProduct)) {
            return array();
        }

        return $newProduct;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getPopularProducts(int $limit): array
    {
        $popularProducts = $this->viewProductRepository->findPopularProducts($limit);

        if (is_null($popularProducts)) {
            return array();
        }

        $products = array();

        foreach ($popularProducts as $row) {
            $product = $this->productRepository->findOneById($row['productId']);

            if (is_null($product)) {
                $null = new NullProductRepository();
                $products[] = $null->findOneById();
                continue;
            }

            $products[] = $product;
        }

        return $products;
    }
}
