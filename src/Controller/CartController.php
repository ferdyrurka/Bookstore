<?php


namespace App\Controller;

use App\Security\SessionAttackInterface;
use App\Service\Controller\CartService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class CartController
 * @package App\Controller
 * @Route("/cart", methods={"GET"})
 */
class CartController extends Controller implements SessionAttackInterface
{
    /**
     * @param int $productId
     * @param int $howMuch
     * @param string|null $cartId
     * @param CartService $service
     * @throws \App\Exception\MinusHowMuchCartException
     * @throws \App\Exception\NotYourCartException
     * @return JsonResponse
     * @Route("/add/{productId}/{howMuch}/{cartId}", methods={"GET"}, name="addProduct.cart")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function addProductAction(
        int $productId,
        int $howMuch,
        CartService $service,
        ?string $cartId = null
    ): JsonResponse {
        return new JsonResponse($service->addProduct($productId, $howMuch, $cartId));
    }

    /**
     * @param int $productId
     * @param int $howMuch
     * @param string $cartId
     * @param CartService $service
     * @throws \App\Exception\MinusHowMuchCartException
     * @throws \App\Exception\NotFoundProductInCartException
     * @throws \App\Exception\NotYourCartException
     * @return JsonResponse
     * @Route("/update/{productId}/{howMuch}/{cartId}", methods={"GET"}, name="updateProduct.cart")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function updateProductAction(
        int $productId,
        int $howMuch,
        string $cartId,
        CartService $service
    ): JsonResponse {
        return new JsonResponse($service->updateProduct($productId, $howMuch, $cartId));
    }

    /**
     * @param int $productId
     * @param string $cartId
     * @param CartService $service
     * @throws \App\Exception\NotFoundProductInCartException
     * @throws \App\Exception\NotYourCartException
     * @return JsonResponse
     * @Route("/delete/{productId}/{cartId}", methods={"GET"}, name="deleteProduct.cart")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function deleteProductAction(int $productId, string $cartId, CartService $service): JsonResponse
    {
        return new JsonResponse($service->deleteProduct($cartId, $productId));
    }

    /**
     * @param string $cartId
     * @param CartService $service
     * @return JsonResponse
     * @throws \App\Exception\NotYourCartException
     * @Route("/delete-cart/{cartId}", methods={"GET"}, name="deleteCart.cart")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function deleteCartAction(string $cartId, CartService $service):  JsonResponse
    {
        return new JsonResponse($service->deleteCart($cartId));
    }
}
