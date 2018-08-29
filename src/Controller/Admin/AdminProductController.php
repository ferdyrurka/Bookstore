<?php


namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductForm;
use App\Security\SessionAttackInterface;
use App\Service\Controller\Admin\AdminProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminProductController
 * @package App\Controller\Admin
 * @Route("/admin1999")
 */
class AdminProductController extends Controller implements SessionAttackInterface
{

    /**
     * @param AdminProductService $service
     * @return array
     * @Route("/products", methods={"GET"}, name="index.adminProduct")
     * @Template("admin/product/index.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexAction(AdminProductService $service): array
    {
        return array(
            'products' => $service->getAll(),
        );
    }

    /**
     * @param Request $request
     * @return array
     * @Route("/create-product", methods={"GET"}, name="create.adminProduct")
     * @IsGranted("ROLE_ADMIN")
     * @Template("admin/product/create.html.twig")
     */
    public function createProductAction(Request $request): array
    {
        $form = $this->createForm(ProductForm::class, new Product());
        $form->handleRequest($request);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request $request
     * @param AdminProductService $service
     * @return Response
     * @Route("/create-product", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function saveProductAction(Request $request, AdminProductService $service): Response
    {
        $form = $this->createForm(ProductForm::class, new Product());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->createProduct($form->getData(), $this->get('upload.file'));

            return $this->redirectToRoute('index.adminProduct');
        }

        return $this->forward(
            'App\Controller\Admin\AdminProductController::createProductAction',
            array(
                'request' => $request,
            )
        );
    }

    /**
     * @param Request $request
     * @param int $productId
     * @param AdminProductService $service
     * @return array
     * @Route("/update-product/{productId}", methods={"GET"}, name="update.adminProduct")
     * @IsGranted("ROLE_ADMIN")
     * @Template("admin/product/update.html.twig")
     */
    public function updateProductAction(Request $request, AdminProductService $service, int $productId): array
    {
        $form = $this->createForm(ProductForm::class, $service->getProduct($productId));
        $form->handleRequest($request);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request $request
     * @param int $productId
     * @param AdminProductService $service
     * @return Response
     * @Route("/update-product/{productId}", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function saveUpdateProductAction(Request $request, AdminProductService $service, int $productId): Response
    {
        $form = $this->createForm(ProductForm::class, $service->getProduct($productId));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->updateProduct($form->getData(), $this->get('upload.file'));

            return $this->redirectToRoute('index.adminProduct');
        }

        return $this->forward(
            'App\Controller\Admin\AdminProductController::updateProductAction',
            array(
                'request' => $request,
                'service' => $service,
                'productId' => $productId,
            )
        );
    }

    /**
     * @param int $productId
     * @param AdminProductService $service
     * @return RedirectResponse
     * @Route("/delete-product/{productId}", methods={"GET"}, name="delete.adminProduct")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteProductAction(int $productId, AdminProductService $service): RedirectResponse
    {
        $service->deleteProduct($productId);

        return $this->redirectToRoute('index.adminProduct');
    }
}
