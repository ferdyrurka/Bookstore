<?php


namespace App\Controller;

use App\Form\ChangePasswordForm;
use App\Form\NewPasswordUserForm;
use App\Form\Model\ChangePasswordModel;
use App\Form\Model\NewPasswordModel;
use App\Security\SessionAttackInterface;
use App\Service\ChangePasswordService;
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
     * @Route("/admin1999/change-password", methods={"GET"}, name="changePassword.adminChangePassword")
     * @Template("change-password/change-password.html.twig")
     * Roles granted in access_controll because two url action.
     */
    public function changePasswordAction(Request $request): array
    {
        $form = $this->createForm(ChangePasswordForm::class, new ChangePasswordModel());

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
     * @Route("/admin1999/change-password", methods={"POST"})
     * Roles granted in access_controll because two url action.
     */
    public function saveChangePasswordAction(Request $request, ChangePasswordService $service): Response
    {
        $form = $this->createForm(ChangePasswordForm::class, new ChangePasswordModel());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveChangePassword($this->getUser(), $form->getData());

            if (in_array('ROLE_USER', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('changePassword.changePassword');
            }
            return $this->redirectToRoute('changePassword.adminChangePassword');
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
        string $token
    ): array {
        $service->getToken($token);

        $form = $this->createForm(NewPasswordUserForm::class, new NewPasswordModel());
        $form->handleRequest($request);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request $request
     * @param ChangePasswordService $service
     * @param string $token
     * @return Response
     * @Route("/change-forgot-password/{token}", methods={"POST"})
     * @Security("not has_role('ROLE_USER')")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function saveNewPasswordAction(
        Request $request,
        ChangePasswordService $service,
        string $token
    ): Response {
        $form = $this->createForm(NewPasswordUserForm::class, new NewPasswordModel());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveNewPassword($service->getToken($token), $form->getData());
            return $this->redirectToRoute('login');
        }

        return $this->forward(
            'App\Controller\ChangePasswordController::forgotPasswordAction',
            array(
                'request' => $request,
                'service' => $service,
                'token' => $token,
            )
        );
    }
}
