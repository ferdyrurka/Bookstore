<?php


namespace App\NullObject\Repository;

use App\Entity\Product;

/**
 * Class NullProductRepository
 * @package App\NullObject\Repository
 */
class NullProductRepository
{

    /**
     * @return Product
     */
    public function findOneById(): Product
    {
        $product = new Product();
        $product->setName('Unknown');
        $product->setMagazine(0);
        $product->setPriceFloat(0);
        $product->setSlug('#');

        return $product;
    }
}
