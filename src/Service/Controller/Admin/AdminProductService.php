<?php


namespace App\Service\Controller\Admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Request\CreateProductRequest;
use App\Request\UpdateProductRequest;
use App\Service\UploadFile;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AdminProductService
 * @package App\Service\Controller\Admin
 */
class AdminProductService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * AdminProductService constructor.
     * @param ProductRepository $productRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $products = $this->productRepository->findAll();

        if (!$products) {
            return array();
        }

        return $products;
    }

    /**
     * @param CreateProductRequest $productRequest
     * @param UploadFile $uploadFile
     */
    public function createProduct(CreateProductRequest $productRequest, UploadFile $uploadFile): void
    {
        $product = new Product();
        $product->setName($productRequest->getName());
        $product->setPrice($productRequest->getPrice() * 100);
        $product->setMagazine($productRequest->getMagazine());

        $time = new \DateTime("now");
        $time->setTimezone(new \DateTimeZone('Europe/Warsaw'));

        $product->setCreatedAt($time);
        $product->setDescription($productRequest->getDescription());
        $product->setCategoryReferences($productRequest->getCategories()[0]->getCategoriesId());

        if (!empty($image = $productRequest->getUploadProductImage()[0]->getProductImage())) {
            $this->em->persist($uploadFile->upload($image));
        }

        $this->em->persist($product);
        $this->em->flush();
    }

    /**
     * @param UpdateProductRequest $productRequest
     * @param Product $product
     * @param UploadFile $uploadFile
     */
    public function updateProduct(UpdateProductRequest $productRequest, Product $product, UploadFile $uploadFile): void
    {
        $product->setName($productRequest->getName());
        $product->setPrice($productRequest->getPrice() * 100);
        $product->setMagazine($productRequest->getMagazine());
        $product->setDescription($productRequest->getDescription());
        $product->setCategoryReferences($productRequest->getCategories()[0]->getCategoriesId());

        if (!empty($image = $productRequest->getUploadProductImage()[0]->getProductImage())) {
            $productImage = $uploadFile->upload($image);
            $product->setProductImageReferences($productImage);
            $this->em->persist($productImage);
        }

        $this->em->persist($product);
        $this->em->flush();
    }

    /**
     * @param int $productId
     * @return Product
     */
    public function getProduct(int $productId): Product
    {
        $product =  $this->productRepository->getOneById($productId);
        $product->setPriceFloat($product->getPrice() / 100);

        return $product;
    }

    /**
     * @param int $productId
     */
    public function deleteProduct(int $productId): void
    {
        $product = $this->getProduct($productId);

        if (!empty($image = $product->getProductImageReferences())) {
            $this->em->remove($image);
        }

        $this->em->remove($product);
        $this->em->flush();
    }
}
