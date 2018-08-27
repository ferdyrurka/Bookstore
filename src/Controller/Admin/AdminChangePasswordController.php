<?php


namespace App\Controller\Admin;

use App\Form\ChangePasswordForm;
use App\Request\ChangePasswordAdminRequest;
use App\Security\SessionAttackInterface;
use App\Service\ChangePasswordService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminChangePasswordController
 * @package App\Controller\Admin
 * @Route("/admin1999")
 */
class AdminChangePasswordController extends Controller implements SessionAttackInterface
{
    /**
     * @param Request $request
     * @return array
     * @Route("/change-password", methods={"GET"}, name="changePassword.adminChangePassword")
     * @Template("admin/change-password/change-password.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function changePasswordAction(Request $request): array
    {
        $form = $this->createForm(ChangePasswordForm::class, new ChangePasswordAdminRequest());

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
     * @IsGranted("ROLE_ADMIN")
     */
    public function saveChangePasswordAction(Request $request, ChangePasswordService $service): Response
    {
        $form = $this->createForm(ChangePasswordForm::class, new ChangePasswordAdminRequest());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->saveChangePassword($this->getUser(), $form->getData());

            return $this->redirectToRoute('logout.adminSecurity');
        }

        return $this->forward(
            'App\Controller\ChangePasswordController::changePasswordAction',
            array(
                'request'=>$request
            )
        );
    }
}
