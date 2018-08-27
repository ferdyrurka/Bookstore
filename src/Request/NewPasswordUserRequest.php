<?php


namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NewPasswordUserRequest
 * @package App\Request
 */
class NewPasswordUserRequest
{
    /**
     * @Assert\NotBlank(message="password.not.empty")
     * @Assert\Length(
     *      min=8,
     *      max=64,
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     */
    private $plainPassword;

    /**
     * @return null|string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
