<?php


namespace App\Service;

use App\Entity\User;
use App\Request\CreateUserOrAdminInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class SecurityService
 * @package App\Service\Controller
 */
class SecurityService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SecurityService constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->encoder = $encoder;
        $this->em = $em;
    }

    /**
     * @param CreateUserOrAdminInterface $createUserRequest
     * @param string $role
     */
    public function saveUser(CreateUserOrAdminInterface $createUserRequest, string $role): void
    {
        $user = new User();
        $user->setUsername($createUserRequest->getUsername());
        $user->setEmail($createUserRequest->getEmail());
        $user->setFirstName($createUserRequest->getFirstName());
        $user->setSurname($createUserRequest->getSurname());
        $user->setRoles($role);
        $user->setStatus(1);

        $user->setPassword($this->encoder->encodePassword($user, $createUserRequest->getPlainPassword()));

        $this->em->persist($user);
        $this->em->flush();
    }
}
