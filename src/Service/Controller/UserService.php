<?php


namespace App\Service\Controller;

use App\Entity\User;
use App\Repository\ForgotPasswordRepository;
use App\Request\UpdateUserRequest;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserService
 * @package App\Service\Controller
 */
class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @param UpdateUserRequest $updateUserRequest
     */
    public function savePersonalDetails(User $user, UpdateUserRequest $updateUserRequest): void
    {
        $user->setFirstName($updateUserRequest->getFirstName());
        $user->setSurname($updateUserRequest->getSurname());
        $user->setEmail($updateUserRequest->getEmail());

        $this->em->persist($user);
        $this->em->flush();
    }
}
