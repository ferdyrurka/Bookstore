<?php


namespace App\Controller\Admin;

use App\Form\UpdateUserForm;
use App\Request\UpdateUserRequest;
use App\Security\SessionAttackInterface;
use App\Service\Controller\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminUserController
 * @package App\Controller\Admin
 * @Route("/admin1999")
 */
class AdminController extends Controller implements SessionAttackInterface
{
    /**
     * @param Request $request
     * @return array
     * @Route("/personal-details", methods={"GET"}, name="changePersonalDetails.adminUser")
     * @Template("admin/user/personal-details.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function changePersonalDetailsAction(Request $request): array
    {
        $form = $this->createForm(UpdateUserForm::class, new UpdateUserRequest($this->getUser()));
        $form->handleRequest($request);

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request $request
     * @param UserService $service
     * @return Response
     * @Route("/personal-details", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function savePersonalDetailsAction(Request $request, UserService $service): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateUserForm::class, new UpdateUserRequest($user));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->savePersonalDetails($user, $form->getData());

            return $this->redirectToRoute('changePersonalDetails.adminUser');
        }

        return $this->forward(
            'App\Controller\Admin\AdminController::changePersonalDetailsAction',
            array(
                'request' => $request
            )
        );
    }
}
