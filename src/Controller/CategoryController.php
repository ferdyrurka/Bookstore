<?php


namespace App\Controller;

use App\Security\SessionAttackInterface;
use App\Service\Controller\CategoryService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CategoryController
 * @package App\Controller
 */
class CategoryController extends Controller implements SessionAttackInterface
{
    /**
     * @param CategoryService $service
     * @return array
     * @Route("/categories", methods={"GET"}, name="index.category")
     * @Template("category/index.html.twig")
     */
    public function indexAction(CategoryService $service): array
    {
        return array('categories'=>$service->getCategories());
    }

    /**
     * @param CategoryService $service
     * @param string $slug
     * @return array
     * @Route("/products-in-category/{slug}", methods={"GET"}, name="productsCategoryList.category")
     * @Template("category/products-category-list.html.twig")
     */
    public function productsCategoryListAction(
        CategoryService $service,
        string $slug
    ): array {
        return array('products' => $service->getProductList($slug));
    }
}
