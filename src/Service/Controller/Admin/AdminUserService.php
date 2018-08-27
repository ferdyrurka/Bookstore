<?php


namespace App\Service\Controller\Admin;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AdminUserService
 * @package App\Service\Controller\Admin
 */
class AdminUserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * AdminUserService constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $users = $this->userRepository->findAll();

        if (!$users) {
            return array();
        }

        return $users;
    }

    /**
     * @param int $userId
     * @param int $status
     */
    private function changeStatusUser(int $userId, int $status): void
    {
        $user = $this->userRepository->getOneById($userId);

        $user->setStatus($status);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param int $userId
     */
    public function disableAccount(int $userId): void
    {
        $this->changeStatusUser($userId, 0);
    }

    /**
     * @param int $userId
     */
    public function activateAccount(int $userId): void
    {
        $this->changeStatusUser($userId, 1);
    }

    /**
     * @param int $userId
     */
    public function deleteAccount(int $userId): void
    {
        $this->em->remove($this->userRepository->getOneById($userId));
        $this->em->flush();
    }
}