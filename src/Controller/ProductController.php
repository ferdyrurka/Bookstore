<?php


namespace App\Controller;

use App\Security\SessionAttackInterface;
use App\Service\Controller\ProductService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends Controller implements SessionAttackInterface
{
    /**
     * @param ProductService $service
     * @param string $slug
     * @return array
     * @throws \Exception
     * @Route("/product/{slug}", methods={"GET"}, name="index.product")
     * @Template("product/index.html.twig")
     */
    public function indexAction(
        ProductService $service,
        string $slug
    ): array {
        if (!empty($userId = $this->getUser())) {
            $userId = $userId->getId();
        } else {
            $userId = null;
        }

        return array(
            'product' => $service->getProductBySlug(
                $slug,
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


    /**
     * @param ProductService $service
     * @param string $slug
     * @return array
     * @Route("/products-in-category/{slug}", methods={"GET"}, name="productsCategoryList.product")
     * @Template("product/products-category-list.html.twig")
     */
    public function productsCategoryListAction(ProductService $service, string $slug): array
    {
        return array('products' => $service->getProductInCategory($slug));
    }
}
