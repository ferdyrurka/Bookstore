<?php

namespace App\Tests\Service\Controller;

use App\Entity\DeliveryMethod;
use App\Entity\Order;
use App\Entity\PriceMethod;
use App\Entity\Product;
use App\Entity\User;
use App\Model\SendMailOrder;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Request\CreateOrderRequest;
use App\Request\DeliveryMethodRequest;
use App\Request\PriceMethodRequest;
use App\Service\Controller\CartService;
use App\Service\Controller\OrderService;
use App\Service\Controller\ProductService;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;
use \Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class OrderServiceTest
 * @package App\Tests\Service
 */
class OrderServiceTest extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $cartService;
    private $productService;
    private $orderService;

    public function setUp(): void
    {
        $this->cartService = Mockery::mock(CartService::class);
        $this->productService = Mockery::mock(ProductService::class);

        $this->orderService = new OrderService($this->cartService, $this->productService);

        parent::setUp();
    }


    public function testGetFilledForm(): void
    {
        $orderRepository = Mockery::mock(OrderRepository::class);

        $result = $this->orderService->getFilledForm($orderRepository);
        $this->assertInstanceOf(CreateOrderRequest::class, $result);
        $this->assertNull($result->getFirstName());
        $this->assertNull($result->getSurname());
        $this->assertNull($result->getEmail());

        /**
         * Using second tests
         */
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getId')->times(2)->andReturn(1);
        $user->shouldReceive('getSurname')->times(2)->andReturn('Surname');
        $user->shouldReceive('getFirstName')->times(2)->andReturn('First Name');
        $user->shouldReceive('getEmail')->times(2)->andReturn('kontakt@lukaszstaniszewski.pl');

        $order = Mockery::mock(Order::class);
        $order->shouldReceive('getCity')->once()->andReturn('City');
        $order->shouldReceive('getStreet')->once()->andReturn('Street');
        $order->shouldReceive('getHouseNumber')->once()->andReturn('2/2');
        $order->shouldReceive('getPostCode')->once()->andReturn('11-230');
        $order->shouldReceive('getPhone')->once()->andReturn(123456789);

        $orderRepository->shouldReceive('findOneByUserId')->withArgs(array(1))->times(2)->andReturn(null, $order);

        $result = $this->orderService->getFilledForm($orderRepository, $user);
        $this->assertInstanceOf(CreateOrderRequest::class, $result);

        $result = $this->orderService->getFilledForm($orderRepository, $user);
        $this->assertInstanceOf(CreateOrderRequest::class, $result);
    }

    public function testGetCart(): void
    {
        $session = Mockery::mock(SessionInterface::class);
        $session->shouldReceive('get')->withArgs(array('cart_id'))->times(2)->andReturn(123456789);

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('getSession')->times(2)->andReturn($session);
        $request->shouldReceive('getLocale')->once()->andReturn('pl');

        $this->cartService
            ->shouldReceive('getCart')
            ->once()
            ->withArgs(array(
                123456789,
                'pl',
                ProductService::class,
                false,
                false
            ))
            ->andReturn(array('Cart'))
        ;

        $result = $this->orderService->getCart(123456789, $request);
        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));

        $request->shouldReceive('getLocale')->never();

        $this->expectException(Exception::class);
        $this->orderService->getCart(1234567899, $request);
    }

    public function testCreateOrder(): void
    {
        $priceMethod = Mockery::mock(PriceMethodRequest::class);
        $priceMethod->shouldReceive('getPriceMethodId')->once()->andReturn(new PriceMethod());

        $deliveryMethodEntity = Mockery::mock(DeliveryMethod::class);
        $deliveryMethodEntity->shouldReceive('getCost')->once()->andReturn(10.00);

        $deliveryMethod = Mockery::mock(DeliveryMethodRequest::class);
        $deliveryMethod->shouldReceive('getDeliveryMethodId')->once()->andReturn($deliveryMethodEntity);

        $createOrderRequest = Mockery::mock(CreateOrderRequest::class);
        $createOrderRequest->shouldReceive('getFirstName')->times(2)->andReturn('First Name');
        $createOrderRequest->shouldReceive('getSurname')->times(2)->andReturn('Surname');
        $createOrderRequest->shouldReceive('getEmail')->times(2)->andReturn('kontakt@lukaszstaniszewski.pl');
        $createOrderRequest->shouldReceive('getCity')->once()->andReturn('City');
        $createOrderRequest->shouldReceive('getStreet')->once()->andReturn('Street');
        $createOrderRequest->shouldReceive('getHouseNumber')->once()->andReturn('2/2');
        $createOrderRequest->shouldReceive('getPostCode')->once()->andReturn('11-230');
        $createOrderRequest->shouldReceive('getPhone')->once()->andReturn(123456789);
        $createOrderRequest->shouldReceive('getOtherInformation')->once()->andReturnNull();
        $createOrderRequest->shouldReceive('getPriceMethods')->once()->andReturn(array(0 => $priceMethod));
        $createOrderRequest->shouldReceive('getDeliveryMethods')->once()->andReturn(array(0 => $deliveryMethod));

        $session = Mockery::mock(SessionInterface::class);
        $session->shouldReceive('get')->withArgs(array('cart_id'))->times(2)->andReturn(123456789);

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('getSession')->times(2)->andReturn($session);
        $request->shouldReceive('getLocale')->times(1)->andReturn('pl');

        $product = Mockery::mock(Product::class);
        $product->shouldReceive('setMagazine')->withArgs(array(3))->once();
        $product->shouldReceive('getMagazine')->once()->andReturn(4);

        $this->cartService
            ->shouldReceive('getCart')
            ->once()
            ->withArgs(array(
                123456789,
                'pl',
                ProductService::class,
                false,
                true
            ))
            ->andReturn(array(2 => array('how_much' => 1, 'cost' => 10.00, 'obj' => $product)))
        ;

        $this->cartService
            ->shouldReceive('deleteCart')
            ->once()
            ->withArgs(array(123456789, $request))
            ->andReturn(array())
        ;

        $this->productService->shouldReceive('getProduct')->withArgs(array(2, 'pl'))->never()->andReturn($product);

        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('persist')->with(
            Mockery::on(function ($argument) {
                if ($argument instanceof Product || $argument instanceof Order){
                    return true;
                }
                return false;
            })
        )->times(2);
        $em->shouldReceive('flush')->once();

        $sendMail = Mockery::mock(SendMail::class);
        $sendMail->shouldReceive('sendMail')->once()->withArgs([SendMailOrder::class])->andReturn(true);

        $this->orderService->createOrder(123456789, $createOrderRequest, $request, $em, $sendMail);

        $this->expectException(Exception::class);
        $this->orderService->createOrder(1234567890, $createOrderRequest, $request, $em, $sendMail);
    }
}
