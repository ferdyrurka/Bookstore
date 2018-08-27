<?php

namespace App\Service;

use App\Entity\UserSession;
use App\Repository\UserSessionRepository;
use App\Service\UserAgent;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class SessionAttack
 * @package App\Services
 */
class SessionAttack
{
    /**
     * @var UserAgent
     */
    private $userAgent;

    /**
     * @var UserSessionRepository
     */
    private $userSessionRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SessionAttack constructor.
     * @param UserAgent $userAgent
     * @param UserSessionRepository $userSessionRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        UserAgent $userAgent,
        UserSessionRepository $userSessionRepository,
        EntityManagerInterface $em
    ) {
        $this->userAgent = $userAgent;
        $this->userSessionRepository = $userSessionRepository;
        $this->em = $em;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isYourSession() : bool
    {
        if (empty($session = $this->userAgent->getSession())) {
            return true;
        }

        $userAgentDb = $this->userSessionRepository->findOneBySession($session);

        if (empty($userAgentDb)) {
            $userSession = new UserSession();
            $userSession->setUserAgent($this->userAgent->getUserAgent());
            $userSession->setUserIp($this->userAgent->getIp());
            $userSession->setSession($session);

            $this->em->persist($userSession);
            $this->em->flush();

            return true;
        }

        if ($userAgentDb->getUserAgent() == $this->userAgent->getUserAgent()
            && $userAgentDb->getUserIp() == $this->userAgent->getIp()
        ) {
            return true;
        }

        return false;
    }
}
