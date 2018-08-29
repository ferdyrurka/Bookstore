<?php


namespace App\Controller;

use App\Form\CreateOrderForm;
use App\Security\SessionAttackInterface;
use App\Service\Controller\OrderService;
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
     * @return array
     * @throws \App\Exception\CartEmptyException
     * @throws \App\Exception\NotYourCartException
     * @Route("/order/{cartId}", methods={"GET"}, name="index.order")
     * @Template("order/index.html.twig")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function indexAction(string $cartId, OrderService $service, Request $request): array
    {
        $form = $this->createForm(CreateOrderForm::class, $service->getFilledForm($this->getUser()));

        $form->handleRequest($request);

        return array(
            'cart' => $service->getCart($cartId, true),
            'form' => $form->createView()
        );
    }

    /**
     * @param string $cartId
     * @param OrderService $service
     * @param Request $request
     * @return Response
     * @throws \App\Exception\CartEmptyException
     * @throws \App\Exception\NotYourCartException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/order/{cartId}", methods={"POST"})
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function createOrderAction(string $cartId, OrderService $service, Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(CreateOrderForm::class, $service->getFilledForm($user));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->createOrder(
                $cartId,
                $form->getData(),
                $user
            );

            return $this->redirectToRoute('home.home');
        }

        return $this->forward(
            'App\Controller\OrderController::indexAction',
            array(
                'cartId'=>$cartId,
                'orderService' => $service,
                'request'=>$request
            )
        );
    }
}
