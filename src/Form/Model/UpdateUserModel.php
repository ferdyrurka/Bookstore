<?php


namespace App\Form\Model;

use App\Entity\User;
use App\Validator\Constraint\UniqueField;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * Class UpdateUserModel
 * @package App\Form\Model
 * @UniqueField(entityClass="App\Entity\User", field="email")
 */
class UpdateUserModel
{
    /**
     * @var integer
     */
    private $id;

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
     * @Assert\NotBlank(message="password.not.empty")
     * @SecurityAssert\UserPassword(
     *     message="password.is.incorrect"
     * )
     */
    private $password;

    /**
     * UpdateAdminRequest constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->setEmail($user->getEmail());
        $this->setFirstName($user->getFirstName());
        $this->setSurname($user->getSurname());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
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
     * @return string
     */
    public function getSurname(): string
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
     * @return string
     */
    public function getEmail(): string
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