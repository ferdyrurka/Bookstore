<?php


namespace App\Service\Controller\Admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
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
     * @param Product $product
     * @param UploadFile $uploadFile
     */
    public function createProduct(Product $product, UploadFile $uploadFile): void
    {
        $product->setPrice($product->getPriceFloat() * 100);

        $time = new \DateTime("now");
        $time->setTimezone(new \DateTimeZone('Europe/Warsaw'));
        $product->setCreatedAt($time);
        $product->setCategoryReferences($product->getCategoriesId());

        if (!empty($image = $product->getUploadProductImage()[0]->getProductImage())) {
            $this->em->persist($uploadFile->upload($image));
        }

        $this->em->persist($product);
        $this->em->flush();
    }

    /**
     * @param Product $product
     * @param UploadFile $uploadFile
     */
    public function updateProduct(Product $product, UploadFile $uploadFile): void
    {
        $product->setPrice($product->getPriceFloat() * 100);
        $product->setCategoryReferences($product->getCategoriesId());

        if (!empty($image = $product->getUploadProductImage()[0]->getProductImage())) {
            $this->em->persist($uploadFile->upload($image));
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
