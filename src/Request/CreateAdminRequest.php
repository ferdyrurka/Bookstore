<?php


namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use App\Validator\Constraint\UniqueField;

/**
 * Class CreateAdminRequest
 * @package App\Request
 * @UniqueField(entityClass="App\Entity\User", field="username", checkId=false)
 * @UniqueField(entityClass="App\Entity\User", field="email", checkId=false)
 */
class CreateAdminRequest implements CreateUserOrAdminInterface
{
    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      min=8,
     *      max=16,
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     * @Assert\Regex(
     *     pattern="/^([A-Z]){1,1}([A-Z|a-z|0-9|_]){7,15}$/",
     *     message="incorrect data provided"
     * )
     */
    private $username;


    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      min=3,
     *      max=24,
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     */
    private $firstName;


    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      min=4,
     *      max=32,
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     */
    private $surname;


    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      min=8,
     *      max=64,
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     * @Assert\Email(
     *     message = "incorrect data provided",
     * )
     */
    private $email;


    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      min=12,
     *      max=64,
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     */
    private $plainPassword;

    /**
     * @Assert\NotBlank(message="password.not.empty")
     * @SecurityAssert\UserPassword(
     *     message="password.is.incorrect"
     * )
     */
    private $adminPassword;

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
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null|string
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

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

    /**
     * @return null|string
     */
    public function getAdminPassword(): ?string
    {
        return $this->adminPassword;
    }

    /**
     * @param string $adminPassword
     */
    public function setAdminPassword(string $adminPassword): void
    {
        $this->adminPassword = $adminPassword;
    }
}
