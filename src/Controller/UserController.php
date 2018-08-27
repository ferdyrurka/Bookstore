<?php


namespace App\Controller;

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
 * Class UserController
 * @package App\Controller
 */
class UserController extends Controller implements SessionAttackInterface
{
    /**
     * @param Request $request
     * @return array
     * @Route("/personal-details", methods={"GET"}, name="changePersonalDetails.user")
     * @Template("user/personal-details.html.twig")
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
     */
    public function savePersonalDetailsAction(Request $request, UserService $service): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateUserForm::class, new UpdateUserRequest($user));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->savePersonalDetails($user, $form->getData());

            return $this->redirectToRoute('changePersonalDetails.user');
        }

        return $this->forward(
            'App\Controller\UserController::changePersonalDetailsAction',
            array(
                'request' => $request
            )
        );
    }
}
