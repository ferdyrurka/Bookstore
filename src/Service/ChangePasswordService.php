<?php


namespace App\Service;

use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Exception\TokenNotFoundException;
use App\Repository\ForgotPasswordRepository;
use App\Request\ChangePasswordUserRequest;
use App\Request\NewPasswordUserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ChangePasswordService
 * @package App\Service\Controller
 */
class ChangePasswordService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * ChangePasswordService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * @param User $user
     * @param ChangePasswordUserRequest $passwordUserRequest
     */
    public function saveChangePassword(User $user, ChangePasswordUserRequest $passwordUserRequest): void
    {
        $user->setPassword($this->encoder->encodePassword($user, $passwordUserRequest->getPlainPassword()));

        $this->savePasswordUser($user);
    }

    /**
     * @param ForgotPassword $forgotPassword
     * @param NewPasswordUserRequest $newPasswordUserRequest
     */
    public function saveNewPassword(
        ForgotPassword $forgotPassword,
        NewPasswordUserRequest $newPasswordUserRequest
    ): void {
        $user = $forgotPassword->getUserReferences();

        $user->setPassword($this->encoder->encodePassword($user, $newPasswordUserRequest->getPlainPassword()));

        $this->em->remove($forgotPassword);

        $this->savePasswordUser($user);
    }

    /**
     * @param string $token
     * @param ForgotPasswordRepository $forgotPasswordRepository
     * @return ForgotPassword
     */
    public function getToken(string $token, ForgotPasswordRepository $forgotPasswordRepository): ForgotPassword
    {
        $token = $forgotPasswordRepository->getOneByToken($token);

        $time = new \DateTime("now");
        if (strtotime($token->getTimeCreate()) < strtotime($time->format('Y-m-d H:i:s'))) {
            $this->em->remove($token);
            $this->em->flush();

            throw new TokenNotFoundException('This token not found.');
        }

        return $token;
    }

    /**
     * @param User $user
     */
    private function savePasswordUser(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
