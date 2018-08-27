<?php


namespace App\Controller;

use App\Form\ChangePasswordForm;
use App\Form\NewPasswordUserForm;
use App\Repository\ForgotPasswordRepository;
use App\Request\ChangePasswordUserRequest;
use App\Request\NewPasswordUserRequest;
use App\Security\SessionAttackInterface;
use App\Service\ChangePasswordService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ChangePasswordController
 * @package App\Controller
 */
class ChangePasswordController extends Controller implements SessionAttackInterface
{
    /**
     * @param Request $request
     * @return array
     * @Route("/change-password", methods={"GET"}, name="changePassword.changePassword")
     * @Template("change-password/change-password.html.twig")
     * @IsGranted("ROLE_USER")
     */
    public function changePasswordAction(Request $request): array
    {
        $form = $this->createForm(ChangePasswordForm::class, new ChangePasswordUserRequest());

        $form->handleRequest($request);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param Request $request
     * @param ChangePasswordService $service
     * @return Response
     * @Route("/change-password", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function saveChangePasswordAction(Request $request, ChangePasswordService $service): Response
    {
        $form = $this->createForm(ChangePasswordForm::class, new ChangePasswordUserRequest());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->saveChangePassword($this->getUser(), $form->getData());

            return $this->redirectToRoute('changePassword.changePassword');
        }

        return $this->forward(
            'App\Controller\ChangePasswordController::changePasswordAction',
            array(
                'request'=>$request
            )
        );
    }

    /**
     * @param Request $request
     * @param ChangePasswordService $service
     * @param ForgotPasswordRepository $forgotPasswordRepository
     * @param string $token
     * @return array
     * @Route("/change-forgot-password/{token}", methods={"GET"}, name="forgotPassword.changePassword")
     * @Template("change-password/change-forgot-password.html.twig")
     * @Security("not has_role('ROLE_ADMIN')")
     * @Security("not has_role('ROLE_USER')")
     */
    public function forgotPasswordAction(
        Request $request,
        ChangePasswordService $service,
        ForgotPasswordRepository $forgotPasswordRepository,
        string $token
    ): array {
        $service->getToken($token, $forgotPasswordRepository);

        $form = $this->createForm(NewPasswordUserForm::class, new NewPasswordUserRequest());
        $form->handleRequest($request);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request $request
     * @param ChangePasswordService $service
     * @param ForgotPasswordRepository $forgotPasswordRepository
     * @param string $token
     * @return Response
     * @Route("/change-forgot-password/{token}", methods={"POST"})
     * @Security("not has_role('ROLE_USER')")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function saveNewPasswordAction(
        Request $request,
        ChangePasswordService $service,
        ForgotPasswordRepository $forgotPasswordRepository,
        string $token
    ): Response {
        $form = $this->createForm(NewPasswordUserForm::class, new NewPasswordUserRequest());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->saveNewPassword($service->getToken($token, $forgotPasswordRepository), $form->getData());
            return $this->redirectToRoute('login');
        }

        return $this->forward(
            'App\Controller\ChangePasswordController::forgotPasswordAction',
            array(
                'request' => $request,
                'service' => $service,
                'token' => $token,
                'forgotPasswordRepository' => $forgotPasswordRepository,
            )
        );
    }
}
