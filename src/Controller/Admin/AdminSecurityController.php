<?php


namespace App\Controller\Admin;

use App\Form\CreateAdminForm;
use App\Form\SignInForm;
use App\Request\CreateAdminRequest;
use App\Request\SignInRequest;
use App\Security\SessionAttackInterface;
use App\Service\SecurityService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\TranslatorInterface;

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
        $form = $this->createForm(CreateAdminForm::class, new CreateAdminRequest());

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
        $form = $this->createForm(CreateAdminForm::class, new CreateAdminRequest());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->saveUser($form->getData(), 'ROLE_ADMIN');

            return $this->redirectToRoute('index.adminUser');
        }

        return $this->forward(
            'App\Controller\Admin\AdminSecurityController:createAdminAction',
            array('request'=>$request)
        );
    }

    /**
     * @param TranslatorInterface $translator
     * @param AuthenticationUtils $authenticationUtils
     * @return array
     * @Route("/admin1999", methods={"GET", "POST"}, name="login.adminSecurity")
     * @Template("admin/security/index.html.twig")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function indexAction(TranslatorInterface $translator, AuthenticationUtils $authenticationUtils): array
    {
        $form = $this->createForm(SignInForm::class, new SignInRequest());

        $error = $authenticationUtils->getLastAuthenticationError();

        if (!empty($error)) {
            $error = $translator->trans($error->getMessage());
        }

        $csrfToken = $this->tokenManager
            ? $this->tokenManager->getToken('authenticate')->getValue()
            : null;

        return array(
            'form' => $form->createView(),
            'error' => isset($error) ? $error : null,
            'csrf_token' => $csrfToken,
        );
    }

    /**
     * @Route("/admin1999/log-out", methods={"GET"}, name="logout.adminSecurity")
     * @IsGranted("ROLE_ADMIN")
     */
    public function logoutAction(): void
    {
        //
    }
}
