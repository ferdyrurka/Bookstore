<?php


namespace App\Controller;

use App\Form\Model\UpdateUserModel;
use App\Form\UpdateUserForm;
use App\Security\SessionAttackInterface;
use App\Service\Controller\UserService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends Controller implements SessionAttackInterface
{
    /**
     * @param Request $request
     * @return array
     * @Route("/personal-details", methods={"GET"}, name="changePersonalDetails.user")
     * @Route("/admin1999/personal-details", methods={"GET"}, name="changePersonalDetails.adminUser")
     * @Template("user/personal-details.html.twig")
     * Roles granted in access_controll because two url action.
     */
    public function changePersonalDetailsAction(Request $request): array
    {
        $form = $this->createForm(UpdateUserForm::class, new UpdateUserModel($this->getUser()));
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
     * @Route("/admin1999/personal-details", methods={"POST"})
     * Roles granted in access_controll because two url action.
     */
    public function savePersonalDetailsAction(Request $request, UserService $service): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateUserForm::class, new UpdateUserModel($user));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->savePersonalDetails($user, $form->getData());

            if (in_array('ROLE_USER', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('changePersonalDetails.user');
            }
            return $this->redirectToRoute('changePersonalDetails.adminUser');
        }

        return $this->forward(
            'App\Controller\UserController::changePersonalDetailsAction',
            array(
                'request' => $request
            )
        );
    }
}
