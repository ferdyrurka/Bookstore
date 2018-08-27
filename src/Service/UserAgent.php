<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class UserAgent
 * @package App\Services
 */
class UserAgent
{
    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * UserAgent constructor.
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }

    /**
     * @return string
     */
    public function getUserAgent() : string
    {
        return htmlspecialchars($this->request->headers->get('User-Agent'));
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getIp() : string
    {
        $ip = filter_var($this->request->getClientIp(), FILTER_VALIDATE_IP);
        if ($ip == false) {
            throw  new \Exception('Failed filter a ip');
        }
        return $ip;
    }

    /**
     * @return string
     */
    public function getSession() : string
    {
        $session = $this->request->getSession();

        if (!$session->isStarted()) {
            $sessionId = $session->getId();
        } else {
            $session->start();
            $sessionId = $session->getId();
        }

        return $sessionId;
    }
}
