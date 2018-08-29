<?php


namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * Class ChangePasswordUserRequest
 * @package App\Request
 */
class ChangePasswordModel
{
    /**
     * @Assert\NotBlank(message="password.not.empty")
     * @SecurityAssert\UserPassword(
     *     message="password.is.incorrect"
     * )
     */
    private $oldPassword;

    /**
     * @Assert\NotBlank(message="not.blank.fields")
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
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    /**
     * @param string $oldPassword
     */
    public function setOldPassword(string $oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

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
