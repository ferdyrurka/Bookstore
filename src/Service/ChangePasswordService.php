<?php


namespace App\Service;

use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Exception\TokenNotFoundException;
use App\Repository\ForgotPasswordRepository;
use App\Form\Model\ChangePasswordModel;
use App\Form\Model\NewPasswordModel;
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
     * @var ForgotPasswordRepository
     */
    private $forgotPasswordRepository;

    /**
     * ChangePasswordService constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param ForgotPasswordRepository $forgotPasswordRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        ForgotPasswordRepository $forgotPasswordRepository
    ) {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->forgotPasswordRepository = $forgotPasswordRepository;
    }

    /**
     * @param User $user
     * @param ChangePasswordModel $passwordUserRequest
     */
    public function saveChangePassword(User $user, ChangePasswordModel $passwordUserRequest): void
    {
        $user->setPassword($this->encoder->encodePassword($user, $passwordUserRequest->getPlainPassword()));

        $this->savePasswordUser($user);
    }

    /**
     * @param ForgotPassword $forgotPassword
     * @param NewPasswordModel $newPasswordUser
     */
    public function saveNewPassword(ForgotPassword $forgotPassword, NewPasswordModel $newPasswordUser): void
    {
        $user = $forgotPassword->getUserReferences();

        $user->setPassword($this->encoder->encodePassword($user, $newPasswordUser->getPlainPassword()));

        $this->em->remove($forgotPassword);

        $this->savePasswordUser($user);
    }

    /**
     * @param string $token
     * @return ForgotPassword
     */
    public function getToken(string $token): ForgotPassword
    {
        $token = $this->forgotPasswordRepository->getOneByToken($token);

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
