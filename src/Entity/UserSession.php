<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user_session")
 * @ORM\Entity
 */
class UserSession
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="user_agent")
     */
    private $userAgent;

    /**
     * @ORM\Column(type="string")
     */
    private $session;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $userIp;

    /**
     * @return int
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserAgent() :string
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return string
     */
    public function getSession() :string
    {
        return $this->session;
    }

    /**
     * @param string $session
     */
    public function setSession(string $session)
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getUserIp(): string
    {
        return $this->userIp;
    }

    /**
     * @param string $userIp
     * @throws \Exception
     */
    public function setUserIp(string $userIp)
    {
        $userIp = filter_var($userIp, FILTER_VALIDATE_IP);
        if ($userIp == false) {
            throw  new \Exception('Failed filter a ip');
        }
        $this->userIp = $userIp;
    }
}
