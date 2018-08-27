<?php


namespace App\Service\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Model\SendMailOrder;
use App\Repository\OrderRepository;
use App\Request\CreateOrderRequest;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrderService
 * @package App\Service\Controller
 */
class OrderService
{
    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * OrderService constructor.
     * @param CartService $cartService
     * @param ProductService $productService
     */
    public function __construct(CartService $cartService, ProductService $productService)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
    }

    /**
     * @param string $cartId
     * @param CreateOrderRequest $createOrder
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SendMail $sendMail
     * @param User|null $user
     * @throws \App\Exception\CartEmptyException
     * @throws \App\Exception\NotYourCartException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createOrder(
        string $cartId,
        CreateOrderRequest $createOrder,
        Request $request,
        EntityManagerInterface $em,
        SendMail $sendMail,
        ?User $user = null
    ): void {

        $cart = $this->getCart($cartId, $request, false, true);
        $cost = 0;

        foreach ($cart as $key => $value) {
            $product = $value['obj'];
            $product->setMagazine($product->getMagazine() - $value['how_much']);

            $em->persist($product);

            $cost = (float) $cost + (float) $value['cost'];
        }

        $order = new Order();

        $order->setFirstName($createOrder->getFirstName());
        $order->setSurname($createOrder->getSurname());
        $order->setEmail($createOrder->getEmail());
        $order->setCity($createOrder->getCity());
        $order->setStreet($createOrder->getStreet());
        $order->setHouseNumber($createOrder->getHouseNumber());
        $order->setPostCode($createOrder->getPostCode());
        $order->setPhone($createOrder->getPhone());
        $order->setOtherInformation($createOrder->getOtherInformation());
        $order->setPriceMethodReferences($createOrder->getPriceMethods()[0]->getPriceMethodId());

        $delivery = $createOrder->getDeliveryMethods()[0]->getDeliveryMethodId();

        $order->setDeliveryMethodReferences($delivery);
        $order->setCost($cost = number_format($cost + $delivery->getCost(), 2));
        $order->setProducts($cart);
        $order->setUserReferences($user);

        $em->persist($order);
        $em->flush();

        $this->cartService->deleteCart($cartId, $request);

        $sendMailOrder = new SendMailOrder();
        $sendMailOrder->setTo($createOrder->getEmail());
        $sendMailOrder->setAttributes(array(
            'firstName' => $createOrder->getFirstName(),
            'surname' => $createOrder->getSurname(),
            'products' => $cart,
            'cost' => $cost,
        ));

        $sendMail->sendMail($sendMailOrder);
    }

    /**
     * @param OrderRepository $orderRepository
     * @param User|null $user
     * @return CreateOrderRequest
     */
    public function getFilledForm(OrderRepository $orderRepository, ?User $user = null): CreateOrderRequest
    {
        $createOrder = new CreateOrderRequest();

        if (empty($user)) {
            return $createOrder;
        }

        $createOrder->setSurname($user->getSurname());
        $createOrder->setFirstName($user->getFirstName());
        $createOrder->setEmail($user->getEmail());

        $lastOrder = $orderRepository->findOneByUserId($user->getId());

        if (!$lastOrder) {
            return $createOrder;
        }

        $createOrder->setCity($lastOrder->getCity());
        $createOrder->setStreet($lastOrder->getStreet());
        $createOrder->setHouseNumber($lastOrder->getHouseNumber());
        $createOrder->setPostCode($lastOrder->getPostCode());
        $createOrder->setPhone($lastOrder->getPhone());

        return $createOrder;
    }

    /**
     * @param string $cartId
     * @param Request $request
     * @param bool $magazine
     * @param bool $objProduct
     * @return array
     * @throws \App\Exception\CartEmptyException
     * @throws \App\Exception\NotYourCartException
     * @throws \Exception
     */
    public function getCart(string $cartId, Request $request, bool $magazine = false, bool $objProduct = false): array
    {
        if ($request->getSession()->get('cart_id') != $cartId) {
            throw new \Exception('This cart It\'s not yours');
        }

        $cartId = htmlspecialchars($cartId);
        $cartId = str_replace(' ', '', $cartId);

        return $this->cartService->getCart(
            $cartId,
            $request->getLocale(),
            $this->productService,
            $magazine,
            $objProduct
        );
    }
}
