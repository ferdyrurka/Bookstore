<?php


namespace App\Service\Controller;

use App\Entity\Product;
use App\Model\Tax;
use App\NullObject\Service\Controller\NullProductService;
use App\Repository\CategoryRepository;
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
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var ViewProductService
     */
    private $viewProductService;

    /**
     * ProductService constructor.
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param ViewProductService $viewProductService
     */
    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        ViewProductService $viewProductService
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->viewProductService = $viewProductService;
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
     * @param int|null $userId
     * @return Product
     * @throws \Exception
     */
    public function getProductBySlug(string $slug, ?int $userId = null): Product
    {
        $slug = htmlspecialchars($slug);
        $slug = str_replace(' ', '', $slug);

        $product = $this->productRepository->getOneBySlug($slug);
        $product->setPriceFloat($this->calculateTax($product->getPrice() / 100));

        $this->viewProductService->addView($product->getId(), $userId);

        return $product;
    }

    /**
     * @param string $slug
     * @return array
     */
    public function getProductInCategory(string $slug): array
    {
        $slug = htmlspecialchars($slug);
        $slug = str_replace(' ', '', $slug);

        $category = $this->categoryRepository->getOneBySlug($slug)->getProductReferences()->getValues();

        if (is_null($category)) {
            return array();
        }

        return $category;
    }

    /**
     * @param float $price
     * @return float
     * @throws \Exception
     */
    private function calculateTax(float $price): float
    {
        $tax = new Tax();
        $tax->setPricesWithTax('pl', $price);

        return $tax->getPriceWithTax();
    }

    /**
     * @param int $productId
     * @return Product
     * @throws \Exception
     */
    public function getProduct(int $productId): Product
    {
        $product = $this->productRepository->findOneById($productId);

        if (is_null($product)) {
            $null = new NullProductService();
            return $null->getProduct();
        }

        $product->setPriceFloat($this->calculateTax($product->getPrice() / 100));

        return $product;
    }
}
