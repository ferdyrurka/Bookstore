<?php


namespace App\Repository;

use App\Entity\Cart;
use App\Exception\CartIdNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CartRepository
 * @package App\Repository
 */
class CartRepository extends ServiceEntityRepository
{

    /**
     * CartRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    /**
     * @param string $cartId
     * @return Cart
     */
    public function getOneByCartId(string $cartId): Cart
    {
        $cart = $this->findOneBy(array(
            'cartId' => $cartId
        ));

        if (!$cart) {
            throw new CartIdNotFoundException('Does cart not exist');
        }

        return $cart;
    }
}
