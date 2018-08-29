<?php

namespace App\Tests\Service\Controller;

use App\Entity\DeliveryMethod;
use App\Entity\Order;
use App\Entity\PriceMethod;
use App\Entity\Product;
use App\Entity\User;
use App\Model\SendMailOrder;
use App\Repository\OrderRepository;
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
    private $orderService;
    private $orderRepository;
    private $em;
    private $sendMail;

    public function setUp(): void
    {
        $this->cartService = Mockery::mock(CartService::class);
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->orderRepository = Mockery::mock(OrderRepository::class);
        $this->sendMail = Mockery::mock(SendMail::class);

        $this->orderService = new OrderService($this->cartService, $this->em, $this->orderRepository, $this->sendMail);

        parent::setUp();
    }


    public function testGetFilledForm(): void
    {
        $result = $this->orderService->getFilledForm();
        $this->assertInstanceOf(Order::class, $result);
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

        $this->orderRepository->shouldReceive('findOneByUserId')->withArgs(array(1))->times(2)->andReturn(null, $order);

        $result = $this->orderService->getFilledForm($user);
        $this->assertInstanceOf(Order::class, $result);

        $result = $this->orderService->getFilledForm($user);
        $this->assertInstanceOf(Order::class, $result);
    }

    public function testGetCart(): void
    {
        $this->cartService
            ->shouldReceive('getCart')
            ->once()
            ->withArgs(array(
                123456789,
                false,
                false
            ))
            ->andReturn(array('Cart'))
        ;

        $result = $this->orderService->getCart(123456789);
        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));

        $this->expectException(Exception::class);
        $this->orderService->getCart(1234567899);
    }

    public function testCreateOrder(): void
    {
        $priceMethod = Mockery::mock(PriceMethod::class);
        $priceMethod->shouldReceive('getPriceMethodId')->once()->andReturn(new PriceMethod());

        $deliveryMethodEntity = Mockery::mock(DeliveryMethod::class);
        $deliveryMethodEntity->shouldReceive('getCost')->once()->andReturn(10.00);

        $deliveryMethod = Mockery::mock(DeliveryMethod::class);
        $deliveryMethod->shouldReceive('getDeliveryMethodId')->once()->andReturn($deliveryMethodEntity);

        $order = Mockery::mock(Order::class);
        $order->shouldReceive('getFirstName')->once()->andReturn('First Name');
        $order->shouldReceive('getSurname')->once()->andReturn('Surname');
        $order->shouldReceive('getEmail')->once()->andReturn('kontakt@lukaszstaniszewski.pl');
        $order->shouldReceive('getPriceMethods')->once()->andReturn(array(0 => $priceMethod));
        $order->shouldReceive('getDeliveryMethods')->once()->andReturn(array(0 => $deliveryMethod));
        $order->shouldReceive('setPriceMethodReferences')->once()->withArgs([PriceMethod::class]);
        $order->shouldReceive('setDeliveryMethodReferences')->once()->withArgs([DeliveryMethod::class]);
        $order->shouldReceive('setUserReferences')->once()->withArgs([null]);
        $order->shouldReceive('setCost')->once();
        $order->shouldReceive('setProducts')->once();

        $product = Mockery::mock(Product::class);
        $product->shouldReceive('setMagazine')->withArgs(array(3))->once();
        $product->shouldReceive('getMagazine')->once()->andReturn(4);

        $this->cartService
            ->shouldReceive('getCart')
            ->once()
            ->withArgs(array(
                123456789,
                false,
                true
            ))
            ->andReturn(array(2 => array('how_much' => 1, 'cost' => 10.00, 'obj' => $product)))
        ;

        $this->cartService
            ->shouldReceive('deleteCart')
            ->once()
            ->withArgs(array(123456789))
            ->andReturn(array())
        ;

        $this->em->shouldReceive('persist')->with(
            Mockery::on(function ($argument) {
                if ($argument instanceof Product || $argument instanceof Order){
                    return true;
                }
                return false;
            })
        )->times(2);
        $this->em->shouldReceive('flush')->once();

        $this->sendMail->shouldReceive('sendMail')->once()->withArgs([SendMailOrder::class])->andReturn(true);

        $this->orderService->createOrder(123456789, $order);

        $this->expectException(Exception::class);
        $this->orderService->createOrder(1234567890, $order);
    }
}
