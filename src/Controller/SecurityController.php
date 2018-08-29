<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\CreateUserForm;
use App\Form\SignInForm;
use App\Service\SecurityService;
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
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends Controller
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
     * @Route("/sign-up", methods={"GET"}, name="register.security")
     * @Template("security/register.html.twig")
     * @Security("not has_role('ROLE_USER')")
     */
    public function registerAction(Request $request): array
    {
        $form = $this->createForm(CreateUserForm::class, new User());

        $form->handleRequest($request);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param SecurityService $service
     * @param Request $request
     * @return Response
     * @Route("/sign-up", methods={"POST"})
     * @Security("not has_role('ROLE_USER')")
     */
    public function saveUserAction(SecurityService $service, Request $request): Response
    {
        $form = $this->createForm(CreateUserForm::class, new User());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveUser($form->getData(), 'ROLE_USER');

            return $this->redirectToRoute('login');
        }

        return $this->forward('App\Controller\SecurityController:registerAction', array('request'=>$request));
    }

    /**
     * @param TranslatorInterface $translator
     * @param AuthenticationUtils $authenticationUtils
     * @return array
     * @Route("sign-in", methods={"GET", "POST"}, name="login.security")
     * @Route("/admin1999", methods={"GET", "POST"}, name="login.adminSecurity")
     * @Template("security/index.html.twig")
     * @Security("not has_role('ROLE_USER')")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function indexAction(TranslatorInterface $translator, AuthenticationUtils $authenticationUtils): array
    {
        $form = $this->createForm(SignInForm::class);

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
     * @Route("/log-out", methods={"GET"}, name="logout.security")
     * @Route("/admin1999/log-out", methods={"GET"}, name="logout.adminSecurity")
     */
    public function logoutAction(): void
    {
        //
    }
}
