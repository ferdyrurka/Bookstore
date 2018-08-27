<?php


namespace App\Controller\Admin;

use App\Form\CategoryForm;
use App\Request\CategoryRequest;
use App\Security\SessionAttackInterface;
use App\Service\Controller\Admin\AdminCategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminCategoryController
 * @package App\Controller\Admin
 * @Route("/admin1999")
 */
class AdminCategoryController extends Controller implements SessionAttackInterface
{

    /**
     * @param AdminCategoryService $service
     * @return array
     * @Route("/categories", methods={"GET"}, name="index.adminCategory")
     * @Template("admin/category/index.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexAction(AdminCategoryService $service): array
    {
        return array(
            'categories' => $service->getAll()
        );
    }

    /**
     * @param Request $request
     * @return array
     * @Route("/create-category", methods={"GET"}, name="create.adminCategory")
     * @Template("admin/category/create.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createCategoryAction(Request $request): array
    {
        $form = $this->createForm(CategoryForm::class, new CategoryRequest());
        $form->handleRequest($request);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param AdminCategoryService $service
     * @param Request $request
     * @return Response
     * @Route("/create-category", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function saveCategoryAction(AdminCategoryService $service, Request $request): Response
    {
        $form = $this->createForm(CategoryForm::class, new CategoryRequest());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->createCategory($form->getData());

            return $this->redirectToRoute('index.adminCategory');
        }

        return $this->forward(
            'App\Controller\Admin\AdminCategoryController::createCategoryAction',
            array(
                'request' => $request,
            )
        );
    }

    /**
     * @param AdminCategoryService $service
     * @param Request $request
     * @param integer $categoryId
     * @return array
     * @Route("/update-category/{categoryId}", methods={"GET"}, name="update.adminCategory")
     * @Template("admin/category/update.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function updateCategoryAction(AdminCategoryService $service, Request $request, int $categoryId): array
    {
        $categoryRequest = new CategoryRequest();
        $form = $this->createForm(
            CategoryForm::class,
            $categoryRequest->setFormData(
                $service->getCategory($categoryId)
            )
        );
        $form->handleRequest($request);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param AdminCategoryService $service
     * @param Request $request
     * @param integer $categoryId
     * @return Response
     * @Route("/update-category/{categoryId}", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function saveUpdateCategoryAction(AdminCategoryService $service, Request $request, int $categoryId): Response
    {
        $categoryRequest = new CategoryRequest();
        $form = $this->createForm(
            CategoryForm::class,
            $categoryRequest->setFormData(
                $category = $service->getCategory($categoryId)
            )
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->updateCategory($form->getData(), $category);

            return $this->redirectToRoute('index.adminCategory');
        }

        return $this->forward(
            'App\Controller\Admin\AdminCategoryController::updateCategoryAction',
            array(
                'service' => $service,
                'request' => $request,
                'categoryId' => $categoryId,
            )
        );
    }

    /**
     * @param AdminCategoryService $service
     * @param integer $categoryId
     * @return RedirectResponse
     * @Route("/delete-category/{categoryId}", methods={"GET"}, name="delete.adminCategory")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteCategoryAction(AdminCategoryService $service, int $categoryId): RedirectResponse
    {
        $service->deleteCategory($categoryId);

        return $this->redirectToRoute('index.adminCategory');
    }
}
