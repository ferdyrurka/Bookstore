<?php


namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ForgotPasswordRequest
 * @package App\Request
 */
class ForgotPasswordRequest
{
    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Email(
     *     message = "incorrect data provided",
     * )
     */
    private $email;

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
