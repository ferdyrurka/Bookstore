<?php

namespace App\Tests\Service\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Exception\CartEmptyException;
use App\Exception\MinusHowMuchCartException;
use App\Exception\NotFoundProductInCartException;
use App\Exception\NotYourCartException;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Service\Controller\CartService;
use App\Service\Controller\ProductService;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CartServiceTest
 * @package App\Tests\Service
 */
class CartServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $em;
    private $cartRepository;
    private $cartService;
    private $request;
    private $session;

    public function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->cartRepository = Mockery::mock(CartRepository::class);

        $this->session = Mockery::mock(SessionInterface::class);

        $this->request = Mockery::mock(Request::class);

        $requestStack = Mockery::mock(RequestStack::class);
        $requestStack->shouldReceive('getCurrentRequest')->once()->andReturn($this->request);

        $this->cartService = new CartService($this->em, $this->cartRepository, $requestStack);

        parent::setUp();
    }

    public function testAddProduct(): void
    {
        /**
         * Second product added. Cart is created.
         */
        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive('getProducts')->once()->andReturn(array(1 => array('how_much' => 1)));
        $cart->shouldReceive('setProducts')->once()->withArgs(array(array(1 => array('how_much' => 3))));

        $this->cartRepository->shouldReceive('getOneByCartId')->once()->andReturn($cart);

        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getMagazine')->times(4)->andReturn(5);

        $productRepository = Mockery::mock(ProductRepository::class);
        $productRepository->shouldReceive('getOneById')->times(2)->withArgs(array(1))->andReturn($product);

        $this->em->shouldReceive('flush')->times(2);
        $this->em->shouldReceive('persist')->times(2)->withArgs(array(Cart::class));

        $this->session
            ->shouldReceive('get')
            ->withArgs(array('cart_id'))
            ->times(3)
            ->andReturn(null, 123456789, 123456789)
        ;
        $this->session->shouldReceive('set')->once();
        $this->request->shouldReceive('getSession')->times(4)->andReturn($this->session);

        $result = $this->cartService->addProduct(1, 2, $productRepository);
        $this->assertFalse($result['error']);
        $this->assertEquals(3, $result['how_much']);

        $result = $this->cartService->addProduct(1, 2, $productRepository, 123456789);
        $this->assertFalse($result['error']);
        $this->assertEquals(2, $result['how_much']);
    }

    public function testDeleteProduct(): void
    {
        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive('getProducts')->times(2)->andReturn(array(1 => array('how_much' => 1)));
        $cart->shouldReceive('setProducts')->once()->withArgs(array(array()));

        $this->cartRepository->shouldReceive('getOneByCartId')->withArgs(array(123456789))->times(2)->andReturn($cart);

        $this->em->shouldReceive('persist')->withArgs(array(Cart::class))->once();
        $this->em->shouldReceive('flush')->once();

        $this->session->shouldReceive('get')->withArgs(array('cart_id'))->times(2)->andReturn(123456789);
        $this->request->shouldReceive('getSession')->times(2)->andReturn($this->session);

        $result = $this->cartService->deleteProduct(123456789, 1);
        $this->assertFalse($result['error']);

        $this->expectException(NotFoundProductInCartException::class);
        $this->cartService->deleteProduct(123456789, 2);
    }

    public function testDeleteCart(): void
    {
        $this->em->shouldReceive('remove')->once()->withArgs(array(Cart::class));
        $this->em->shouldReceive('flush')->once();

        $cart = Mockery::mock(Cart::class);

        $this->cartRepository->shouldReceive('getOneByCartId')->withArgs(array(123456789))->once()->andReturn($cart);

        $this->session->shouldReceive('get')->withArgs(array('cart_id'))->once()->andReturn(123456789);
        $this->session->shouldReceive('remove')->withArgs(array('cart_id'))->once();
        $this->request->shouldReceive('getSession')->times(2)->andReturn($this->session);

        $result = $this->cartService->deleteCart(123456789);
        $this->assertFalse($result['error']);
    }

    public function testGetCart(): void
    {
        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive('getProducts')->times(2)->andReturn(array(1 => array('how_much' => 1)), array());

        $this->cartRepository->shouldReceive('getOneByCartId')->withArgs(array(123456789))->times(2)->andReturn($cart);

        $product = new Product();
        $product->setMagazine(5);
        $product->setPriceFloat(10.00);
        $product->setName('Name');

        $productService = Mockery::mock(ProductService::class);
        $productService->shouldReceive('getProduct')->withArgs(array(1,'pl'))->once()->andReturn($product);

        $this->session->shouldReceive('get')->withArgs(array('cart_id'))->times(2)->andReturn(123456789);
        $this->request->shouldReceive('getSession')->times(2)->andReturn($this->session);

        $result = $this->cartService->getCart(123456789, 'pl', $productService, true);
        $this->assertEquals(1, $result[1]['how_much']);
        $this->assertEquals(10, $result[1]['cost']);
        $this->assertEquals('Name', $result[1]['name']);
        $this->assertEquals(5, $result[1]['magazine']);

        $this->expectException(CartEmptyException::class);
        $this->cartService->getCart(123456789, 'pl', $productService);
    }

    public function testUpdateProduct(): void
    {
        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive('getProducts')->times(3)->andReturn(array(1 => array('how_much' => 1)));
        $cart->shouldReceive('setProducts')->once()->withArgs(array(array(1 => array('how_much' => 2))));

        $this->cartRepository->shouldReceive('getOneByCartId')->withArgs(array(123456789))->times(3)->andReturn($cart);

        $this->em->shouldReceive('persist')->withArgs(array(Cart::class))->times(2);
        $this->em->shouldReceive('flush')->times(2);

        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getMagazine')->times(3)->andReturn(5);

        $productRepository = Mockery::mock(ProductRepository::class);
        $productRepository
            ->shouldReceive('findOneById')
            ->withArgs(array(1))
            ->times(3)
            ->andReturn($product, $product, null)
        ;

        $this->session->shouldReceive('get')->withArgs(array('cart_id'))->times(3)->andReturn(123456789);
        $this->request->shouldReceive('getSession')->times(3)->andReturn($this->session);

        $result = $this->cartService->updateProduct(1, 2, 123456789, $productRepository);
        $this->assertFalse($result['error']);
        $this->assertEquals(3, $result['how_much']);

        /**
         * To many pieces
         */

        $result = $this->cartService->updateProduct(1, 6, 123456789, $productRepository);
        $this->assertEquals('Too many pieces of the product.', $result['error']);

        /**
         * Product deleted
         */

        $cart->shouldReceive('setProducts')->once()->withArgs(array(array()));

        $result = $this->cartService->updateProduct(1, 2, 123456789, $productRepository);
        $this->assertEquals('The product ceased to exist.', $result['error']);
    }

    public function testUpdateProductExceptionMinus(): void
    {
        $productRepository = Mockery::mock(ProductRepository::class);

        $this->expectException(MinusHowMuchCartException::class);
        $this->cartService->updateProduct(1, -1, 123456789, $productRepository);
    }

    public function testUpdateProductExceptionNotFound(): void
    {
        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive('getProducts')->once()->andReturn(array(1 => array('how_much' => 1)));

        $this->cartRepository->shouldReceive('getOneByCartId')->withArgs(array(123456789))->once()->andReturn($cart);

        $productRepository = Mockery::mock(ProductRepository::class);

        $this->session->shouldReceive('get')->withArgs(array('cart_id'))->once()->andReturn(123456789);
        $this->request->shouldReceive('getSession')->once()->andReturn($this->session);

        $this->expectException(NotFoundProductInCartException::class);
        $this->cartService->updateProduct(2, 1, 123456789, $productRepository);
    }

    public function testNotYourCart(): void
    {
        $productRepository = Mockery::mock(ProductRepository::class);

        $this->session->shouldReceive('get')->withArgs(array('cart_id'))->once()->andReturn(123456789);
        $this->request->shouldReceive('getSession')->once()->andReturn($this->session);

        $this->expectException(NotYourCartException::class);
        $this->cartService->updateProduct(2, 1, 1234567890, $productRepository);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
