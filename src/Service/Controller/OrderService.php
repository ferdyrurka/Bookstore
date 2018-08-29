<?php


namespace App\Service\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Model\SendMailOrder;
use App\Repository\OrderRepository;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;

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
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var SendMail
     */
    private $sendMail;

    /**
     * OrderService constructor.
     * @param CartService $cartService
     * @param EntityManagerInterface $em
     * @param OrderRepository $orderRepository
     * @param SendMail $sendMail
     */
    public function __construct(
        CartService $cartService,
        EntityManagerInterface $em,
        OrderRepository $orderRepository,
        SendMail $sendMail
    ) {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->orderRepository = $orderRepository;
        $this->sendMail = $sendMail;
    }

    /**
     * @param string $cartId
     * @param Order $order
     * @param User|null $user
     * @throws \App\Exception\CartEmptyException
     * @throws \App\Exception\NotYourCartException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createOrder(string $cartId, Order $order, ?User $user = null): void
    {
        $cart = $this->getCart($cartId, false, true);
        $cost = 0;

        foreach ($cart as $value) {
            $product = $value['obj'];
            $product->setMagazine($product->getMagazine() - $value['how_much']);

            $this->em->persist($product);

            $cost = (float) $cost + (float) $value['cost'];
        }

        $order->setPriceMethodReferences($order->getPriceMethods()[0]->getPriceMethodId());
        $delivery = $order->getDeliveryMethods()[0]->getDeliveryMethodId();

        $order->setDeliveryMethodReferences($delivery);
        $order->setCost($cost = number_format($cost + $delivery->getCost(), 2));
        $order->setProducts($cart);
        $order->setUserReferences($user);

        $this->em->persist($order);
        $this->em->flush();

        $this->cartService->deleteCart($cartId);

        $sendMailOrder = new SendMailOrder();
        $sendMailOrder->setTo($order->getEmail());
        $sendMailOrder->setAttributes(array(
            'firstName' => $order->getFirstName(),
            'surname' => $order->getSurname(),
            'products' => $cart,
            'cost' => $cost,
        ));

        $this->sendMail->sendMail($sendMailOrder);
    }

    /**
     * @param User|null $user
     * @return Order
     */
    public function getFilledForm(?User $user = null): Order
    {
        $createOrder = new Order();

        if (empty($user)) {
            return $createOrder;
        }

        $createOrder->setSurname($user->getSurname());
        $createOrder->setFirstName($user->getFirstName());
        $createOrder->setEmail($user->getEmail());

        $lastOrder = $this->orderRepository->findOneByUserId($user->getId());

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
     * @param bool $magazine
     * @param bool $objProduct
     * @return array
     * @throws \App\Exception\CartEmptyException
     * @throws \App\Exception\NotYourCartException
     * @throws \Exception
     */
    public function getCart(string $cartId, bool $magazine = false, bool $objProduct = false): array
    {
        $cartId = htmlspecialchars($cartId);
        $cartId = str_replace(' ', '', $cartId);

        return $this->cartService->getCart(
            $cartId,
            $magazine,
            $objProduct
        );
    }
}
