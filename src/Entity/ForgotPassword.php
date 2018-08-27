<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForgotPasswordRepository")
 * @ORM\Table(name="forgot_password")
 */
class ForgotPassword
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", unique=true)
     */
    private $token;

    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Email(
     *     message = "email.fail"
     * )
     * @ORM\Column(type="integer", length=11, unique=true, name="user_id")
     */
    private $userId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timeCreate;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userReferences;

    /**
     * Cart constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->token = Uuid::uuid4();
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getTimeCreate(): string
    {
        return $this->timeCreate->format('Y-m-d H:i:s');
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setTimeCreate(\DateTime $dateTime)
    {
        $this->timeCreate = $dateTime;
    }

    /**
     * @return User
     */
    public function getUserReferences(): User
    {
        return $this->userReferences;
    }

    /**
     * @param User $userReferences
     */
    public function setUserReferences(User $userReferences)
    {
        $this->userReferences = $userReferences;
    }
}
