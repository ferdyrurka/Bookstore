<?php


namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Security\SessionAttackInterface;
use App\Service\Controller\Admin\AdminOrderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminOrderController
 * @package App\Controller\Admin
 * @Route("/admin1999")
 */
class AdminOrderController extends Controller implements SessionAttackInterface
{
    /**
     * @param int $orderId
     * @param OrderRepository $orderRepository
     * @return array
     * @throws \App\Exception\OrderNotFoundException
     * @Route("/order/{orderId}", methods={"GET"}, name="index.adminOrder")
     * @Template("admin/order/index.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexAction(int $orderId, OrderRepository $orderRepository): array
    {
        return array(
            'order' => $orderRepository->getOnyById($orderId)
        );
    }

    /**
     * @param AdminOrderService $service
     * @return array
     * @throws \Exception
     * @Route("/orders", methods={"GET"}, name="orderList.adminOrder")
     * @Template("admin/order/order-list.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function orderListAction(AdminOrderService $service): array
    {
        return array(
            'orders' => $service->getAll()
        );
    }
}
