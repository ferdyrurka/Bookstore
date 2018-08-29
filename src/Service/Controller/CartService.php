<?php


namespace App\Service\Controller;

use App\Entity\Cart;
use App\Exception\CartEmptyException;
use App\Exception\MinusHowMuchCartException;
use App\Exception\NotFoundProductInCartException;
use App\Exception\NotYourCartException;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CartService
 * @package App\Service\Controller
 */
class CartService
{

    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * CartService constructor.
     * @param EntityManagerInterface $em
     * @param CartRepository $cartRepository
     * @param RequestStack $requestStack
     * @param ProductRepository $productRepository
     * @param ProductService $productService
     */
    public function __construct(
        EntityManagerInterface $em,
        CartRepository $cartRepository,
        RequestStack $requestStack,
        ProductRepository $productRepository,
        ProductService $productService
    ) {
        $this->cartRepository = $cartRepository;
        $this->em = $em;
        $this->request = $requestStack->getCurrentRequest();
        $this->productRepository = $productRepository;
        $this->productService = $productService;
    }

    /**
     * @param string $cartId
     * @return Cart
     * @throws NotYourCartException
     */
    private function init(string $cartId): Cart
    {

        if ($this->request->getSession()->get('cart_id') != $cartId) {
            throw new NotYourCartException('This cart it\'s not yours.');
        }

        $cartId = htmlspecialchars($cartId);
        $cartId = str_replace(' ', '', $cartId);

        return $this->cartRepository->getOneByCartId($cartId);
    }

    /**
     * @param int $productId
     * @param int $howMuch
     * @param null|string $cartId
     * @return array
     * @throws MinusHowMuchCartException
     * @throws NotYourCartException
     * @throws \Exception
     */
    public function addProduct(
        int $productId,
        int $howMuch,
        ?string $cartId = null
    ): array {

        if ($howMuch < 1) {
            throw new MinusHowMuchCartException('The value provided is too small.');
        }

        if (!empty($cart = $this->request->getSession()->get('cart_id'))) {
            $cartId = $cart;
        }

        if (is_null($cartId)) {
            $cart = new Cart();
            $products = array();
            $session = true;
        } else {
            $cart = $this->init($cartId);
            $products = $cart->getProducts();
        }

        $product = $this->productRepository->getOneById($productId);

        if (isset($products[$productId])) {
            $howMuch = $products[$productId]['how_much'] + $howMuch;
        }

        if ($howMuch > $product->getMagazine()) {
            return array('error' => 'Too many pieces of the product.');
        }

        $products[$productId] = array(
            'how_much' => $howMuch
        );

        $cart->setProducts($products);

        if (isset($session)) {
            $this->request->getSession()->set('cart_id', $cart->getCartId());
            $cartIdToJson = $cart->getCartId();
        }

        $this->em->persist($cart);
        $this->em->flush();

        return array(
            'cart_id' => isset($cartIdToJson) ? $cartIdToJson: false,
            'error' => false,
            'how_much' => $product->getMagazine() - $howMuch,
        );
    }

    /**
     * @param int $productId
     * @param int $howMuch
     * @param string $cartId
     * @return array
     * @throws MinusHowMuchCartException
     * @throws NotFoundProductInCartException
     * @throws NotYourCartException
     */
    public function updateProduct(
        int $productId,
        int $howMuch,
        string $cartId
    ): array {

        if ($howMuch < 1) {
            throw new MinusHowMuchCartException('The value provided is too small.');
        }

        $cart = $this->init($cartId);

        $products = $cart->getProducts();

        if (!isset($products[$productId])) {
            throw new NotFoundProductInCartException('Does product not found in cart');
        }

        $product = $this->productRepository->findOneById($productId);

        if (is_null($product)) {
            unset($products[$productId]);
            $cart->setProducts($products);

            $this->em->persist($cart);
            $this->em->flush();

            return array(
                'error' => 'The product ceased to exist.',
            );
        }

        if ((int) $howMuch > (int) $product->getMagazine()) {
            return array('error' => 'Too many pieces of the product.');
        }

        $products[$productId] = array(
            'how_much' => $howMuch,
        );

        $cart->setProducts($products);

        $this->em->persist($cart);
        $this->em->flush();

        return array(
            'error' => false,
            'how_much' => $product->getMagazine() - $howMuch
        );
    }

    /**
     * @param string $cartId
     * @param int $productId
     * @return array
     * @throws NotFoundProductInCartException
     * @throws NotYourCartException
     */
    public function deleteProduct(string $cartId, int $productId): array
    {
        $cart = $this->init($cartId);

        $products = $cart->getProducts();

        if (!isset($products[$productId])) {
            throw new NotFoundProductInCartException('Does product not found in cart!');
        }

        unset($products[$productId]);
        $cart->setProducts($products);

        $this->em->persist($cart);
        $this->em->flush();

        return array(
            'error' => false
        );
    }

    /**
     * @param string $cartId
     * @return array
     * @throws NotYourCartException
     */
    public function deleteCart(string $cartId): array
    {
        $this->em->remove($this->init($cartId));
        $this->em->flush();

        $this->request->getSession()->remove('cart_id');

        return array(
            'error' => false,
        );
    }

    /**
     * @param string $cartId
     * @param bool $magazine
     * @param bool $objProduct
     * @return array
     * @throws CartEmptyException
     * @throws NotYourCartException
     * @throws \Exception
     */
    public function getCart(
        string $cartId,
        bool $magazine = false,
        bool $objProduct = false
    ): array {
        $products = array();

        foreach ($this->init($cartId)->getProducts() as $key => $row) {
            $product = $this->productService->getProduct($key, 'pl');

            if ($row['how_much'] > $product->getMagazine()) {
                $products[$key]['how_much'] = $product->getMagazine();
            } else {
                $products[$key]['how_much'] = $row['how_much'];
            }

            $products[$key]['cost'] = $product->getPriceFloat() * $products[$key]['how_much'];
            $products[$key]['name'] = $product->getName();

            if ($magazine === true) {
                $products[$key]['magazine'] = $product->getMagazine();
            }

            if ($objProduct === true) {
                $products[$key]['obj'] = $product;
            }
        }

        if (empty($products)) {
            throw new CartEmptyException('Cart is empty!');
        }

        return $products;
    }
}
