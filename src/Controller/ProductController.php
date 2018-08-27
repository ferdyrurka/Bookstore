<?php


namespace App\Controller;

use App\Security\SessionAttackInterface;
use App\Service\Controller\ProductService;
use App\Service\Controller\ViewProductService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends Controller implements SessionAttackInterface
{
    /**
     * @param Request $request
     * @param ProductService $service
     * @param string $slug
     * @param ViewProductService $viewProductService
     * @return array
     * @throws \Exception
     * @Route("/product/{slug}", methods={"GET"}, name="index.product")
     * @Template("product/index.html.twig")
     */
    public function indexAction(
        Request $request,
        ProductService $service,
        string $slug,
        ViewProductService $viewProductService
    ): array {
        if (!empty($userId = $this->getUser())) {
            $userId = $userId->getId();
        } else {
            $userId = null;
        }

        return array(
            'product' => $service->getProductBySlug(
                $slug,
                $request->getLocale(),
                $viewProductService,
                $userId
            )
        );
    }

    /**
     * @param ProductService $service
     * @return array
     * @Route("/products", methods={"GET"}, name="productsList.product")
     * @Template("product/products-list.html.twig")
     */
    public function productsListAction(ProductService $service): array
    {
        return array('products' => $service->getProducts());
    }
}
