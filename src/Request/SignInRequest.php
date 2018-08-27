<?php


namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SignInRequest
 * @package App\Request
 */
class SignInRequest
{
    /**
     * @Assert\NotBlank(message="not.blank.fields")
     */
    private $username;

    /**
     * @Assert\NotBlank(message="not.blank.fields")
     */
    private $password;

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
