<?php


namespace App\Controller\Admin;

use App\Security\SessionAttackInterface;
use App\Service\Controller\Admin\AdminUserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminUserController
 * @package App\Controller\Admin
 * @Route("/admin1999")
 */
class AdminUserController extends Controller implements SessionAttackInterface
{
    /**
     * @param AdminUserService $service
     * @return array
     * @Route("/users", methods={"GET"}, name="index.adminUser")
     * @Template("admin/user/index.html.twig")
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexAction(AdminUserService $service): array
    {
        return array(
            'users' => $service->getAll()
        );
    }

    /**
     * @param int $userId
     * @param AdminUserService $service
     * @return RedirectResponse
     * @Route("/disable-account/{userId}", methods={"GET"}, name="disable.adminUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function disableAccountAction(int $userId, AdminUserService $service): RedirectResponse
    {
        $service->disableAccount($userId);

        return $this->redirectToRoute('index.adminUser');
    }

    /**
     * @param int $userId
     * @param AdminUserService $service
     * @return RedirectResponse
     * @Route("/activate-account/{userId}", methods={"GET"}, name="activate.adminUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function activateAccountAction(int $userId, AdminUserService $service): RedirectResponse
    {
        $service->activateAccount($userId);

        return $this->redirectToRoute('index.adminUser');
    }

    /**
     * @param int $userId
     * @param AdminUserService $service
     * @return RedirectResponse
     * @Route("/delete-account/{userId}", methods={"GET"}, name="delete.adminUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteAccountAction(int $userId, AdminUserService $service): RedirectResponse
    {
        $service->deleteAccount($userId);

        return $this->redirectToRoute('index.adminUser');
    }
}
