<?php


namespace App\Service\Controller;

use App\Entity\User;
use App\Form\Model\UpdateUserModel;
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
     * @param UpdateUserModel $updateUserRequest
     */
    public function savePersonalDetails(User $user, UpdateUserModel $updateUserRequest): void
    {
        $user->setFirstName($updateUserRequest->getFirstName());
        $user->setSurname($updateUserRequest->getSurname());
        $user->setEmail($updateUserRequest->getEmail());

        $this->em->persist($user);
        $this->em->flush();
    }
}
