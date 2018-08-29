<?php


namespace App\Controller;

use App\Form\ForgotPasswordForm;
use App\Form\Model\ForgotPasswordModel;
use App\Security\SessionAttackInterface;
use App\Service\Controller\ForgotPasswordService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ForgotPasswordController
 * @package App\Controller
 */
class ForgotPasswordController extends Controller implements SessionAttackInterface
{
    /**
     * @param Request $request
     * @return array
     * @Route("/forgot-password", methods={"GET"}, name="index.forgotPassword")
     * @Template("forgot-password/index.html.twig")
     * @Security("not has_role('ROLE_USER')")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request): array
    {
        $form = $this->createForm(ForgotPasswordForm::class, new ForgotPasswordModel());

        $form->handleRequest($request);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request $request
     * @param ForgotPasswordService $service
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/forgot-password", methods={"POST"})
     * @Security("not has_role('ROLE_USER')")
     * @Security("not has_role('ROLE_ADMIN')")
     */
    public function createForgotPasswordAction(Request $request, ForgotPasswordService $service): Response
    {
        $form = $this->createForm(ForgotPasswordForm::class, new ForgotPasswordModel());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->createForgotPassword($form->getData());
            return $this->redirectToRoute('home.home');
        }

        return $this->forward('App\Controller\ForgotPasswordController::indexAction', array('request'=>$request));
    }
}
