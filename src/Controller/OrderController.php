<?php


namespace App\Controller;

use App\Form\CreateOrderForm;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Security\SessionAttackInterface;
use App\Service\Controller\OrderService;
use App\Service\SendMail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrderController
 * @package App\Controller
 */
class OrderController extends Controller implements SessionAttackInterface
{
    /**
     * @param string $cartId
     * @param OrderService $service
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @return array
     * @throws \App\Exception\CartEmptyException
     * @Route("/order/{cartId}", methods={"GET"}, name="index.order")
     * @Template("order/index.html.twig")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function indexAction(
        string $cartId,
        OrderService $service,
        Request $request,
        OrderRepository $orderRepository
    ): array {
        $form = $this->createForm(CreateOrderForm::class, $service->getFilledForm($orderRepository, $this->getUser()));

        $form->handleRequest($request);

        return array(
            'cart' => $service->getCart($cartId, $request, true),
            'form' => $form->createView()
        );
    }

    /**
     * @param string $cartId
     * @param OrderService $service
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param SendMail $sendMail
     * @return Response
     * @throws \App\Exception\CartEmptyException
     * @throws \App\Exception\NotYourCartException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/order/{cartId}", methods={"POST"})
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function createOrderAction(
        string $cartId,
        OrderService $service,
        Request $request,
        OrderRepository $orderRepository,
        SendMail $sendMail
    ): Response {
        $user = $this->getUser();
        $form = $this->createForm(CreateOrderForm::class, $service->getFilledForm($orderRepository, $user));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->createOrder(
                $cartId,
                $form->getData(),
                $request,
                $this->getDoctrine()->getManager(),
                $sendMail,
                $user
            );

            return $this->redirectToRoute('home.home');
        }

        return $this->forward(
            'App\Controller\OrderController::indexAction',
            array(
                'cartId'=>$cartId,
                'orderService' => $service,
                'request'=>$request,
                'orderRepository' => $orderRepository
            )
        );
    }
}
