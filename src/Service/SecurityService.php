<?php


namespace App\Service;

use App\Entity\User;
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
     * @param User $user
     * @param string $role
     */
    public function saveUser(User $user, string $role): void
    {
        $user->setRoles($role);
        $user->setStatus(1);

        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));

        $this->em->persist($user);
        $this->em->flush();
    }
}
