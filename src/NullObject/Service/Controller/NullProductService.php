<?php


namespace App\NullObject\Service\Controller;

use App\Entity\Product;

/**
 * Class NullProductService
 * @package App\NullObject\Service\Controller
 */
class NullProductService
{
    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        $product = new Product();
        $product->setName('Unknown');
        $product->setMagazine(0);
        $product->setPriceFloat(0);
        $product->setSlug('#');

        return $product;
    }
}
