<?php


namespace App\Service\Controller;

use App\Entity\Product;
use App\Model\Tax;
use App\NullObject\Service\Controller\NullProductService;
use App\Repository\ProductRepository;

/**
 * Class ProductService
 * @package App\Service\Controller
 */
class ProductService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * ProductService constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        $products = $this->productRepository->findAll();

        if (is_null($products)) {
            return array();
        }

        return $products;
    }

    /**
     * @param string $slug
     * @param string $locale
     * @param ViewProductService $viewProductService
     * @param int|null $userId
     * @return Product
     * @throws \Exception
     */
    public function getProductBySlug(
        string $slug,
        string $locale,
        ViewProductService $viewProductService,
        ?int $userId = null
    ): Product {
        $slug = htmlspecialchars($slug);
        $slug = str_replace(' ', '', $slug);

        $product = $this->productRepository->getOneBySlug($slug);
        $product->setPriceFloat($this->calculateTax($product->getPrice() / 100, $locale));

        $viewProductService->addView($product->getId(), $userId);

        return $product;
    }

    /**
     * @param float $price
     * @param string $locale
     * @return float
     * @throws \Exception
     */
    private function calculateTax(float $price, string $locale): float
    {
        $tax = new Tax();
        $tax->setPricesWithTax($locale, $price);

        return $tax->getPriceWithTax();
    }

    /**
     * @param int $productId
     * @param string $locale
     * @return Product
     * @throws \Exception
     */
    public function getProduct(int $productId, string $locale): Product
    {
        $product = $this->productRepository->findOneById($productId);

        if (is_null($product)) {
            $null = new NullProductService();
            return $null->getProduct();
        }

        $product->setPriceFloat($this->calculateTax($product->getPrice() / 100, $locale));

        return $product;
    }
}
