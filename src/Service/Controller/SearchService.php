<?php


namespace App\Service\Controller;

use App\Repository\ProductRepository;

/**
 * Class SearchService
 * @package App\Service\Controller
 */
class SearchService
{

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * SearchService constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $phrase
     * @return array
     */
    public function searchProducts(string $phrase): array
    {
        $name = htmlspecialchars($phrase);

        $products = $this->productRepository->findByName($name);

        if (is_null($products)) {
            return array();
        }

        return $products;
    }
}
