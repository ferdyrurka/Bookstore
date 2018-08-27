<?php


namespace App\Service\Controller;

use App\Entity\ForgotPassword;
use App\Model\SendMailForgotPassword;
use App\Repository\ForgotPasswordRepository;
use App\Repository\UserRepository;
use App\Request\ForgotPasswordRequest;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ForgotPasswordService
 * @package App\Service\Controller
 */
class ForgotPasswordService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ForgotPasswordRepository
     */
    private $forgotPasswordRepository;

    /**
     * @var SendMail
     */
    private $sendMail;

    /**
     * ForgotPasswordService constructor.
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param ForgotPasswordRepository $forgotPasswordRepository
     * @param SendMail $sendMail
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ForgotPasswordRepository $forgotPasswordRepository,
        SendMail $sendMail
    ) {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->forgotPasswordRepository = $forgotPasswordRepository;
        $this->sendMail = $sendMail;
    }

    /**
     * @param ForgotPasswordRequest $forgotPasswordRequest
     * @throws \Exception
     */
    public function createForgotPassword(ForgotPasswordRequest $forgotPasswordRequest): void
    {
        $user = $this->userRepository->getOneByEmail($forgotPasswordRequest->getEmail());

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new \Exception('Your account is admin. Administrator don\'t permission to forgot password.');
        }

        if ($forgot = $this->forgotPasswordRepository->findOneByUserId($user->getId())) {
            $forgotPassword = $forgot;
        } else {
            $forgotPassword = new ForgotPassword();
        }

        $forgotPassword->setUserReferences($user);
        $time = new \DateTime("+ 1hour");
        $time->setTimezone(new \DateTimeZone('Europe/Warsaw'));
        $forgotPassword->setTimeCreate($time);

        $this->em->persist($forgotPassword);
        $this->em->flush();

        $sendMailForgotPassword = new SendMailForgotPassword();
        $sendMailForgotPassword->setTo($forgotPasswordRequest->getEmail());
        $sendMailForgotPassword->setAttributes(array(
            'token' => $forgotPassword->getToken(),
        ));

        $this->sendMail->sendMail($sendMailForgotPassword);
    }
}
