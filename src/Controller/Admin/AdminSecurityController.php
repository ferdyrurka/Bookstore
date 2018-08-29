<?php


namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\CreateAdminForm;
use App\Security\SessionAttackInterface;
use App\Service\SecurityService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class AdminSecurityController
 * @package App\Controller\Admin
 */
class AdminSecurityController extends Controller implements SessionAttackInterface
{
    private $tokenManager;

    /**
     * SecurityController constructor.
     * @param CsrfTokenManagerInterface|null $tokenManager
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager = null)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param Request $request
     * @return array
     * @Route("/admin1999/create-admin", methods={"GET"}, name="createAdmin.adminSecurity")
     * @Template("admin/security/add-admin.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createAdminAction(Request $request): array
    {
        $form = $this->createForm(CreateAdminForm::class, new User());

        $form->handleRequest($request);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param SecurityService $service
     * @param Request $request
     * @return Response
     * @Route("/admin1999/create-admin", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function saveAdminAction(SecurityService $service, Request $request): Response
    {
        $form = $this->createForm(CreateAdminForm::class, new User());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveUser($form->getData(), 'ROLE_ADMIN');

            return $this->redirectToRoute('index.adminUser');
        }

        return $this->forward(
            'App\Controller\Admin\AdminSecurityController:createAdminAction',
            array('request'=>$request)
        );
    }
}
