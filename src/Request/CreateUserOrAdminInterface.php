<?php


namespace App\Request;

/**
 * Interface CreateUserOrAdminInterface
 * @package App\Request
 */
interface CreateUserOrAdminInterface
{
    /**
     * @return null|string
     */
    public function getUsername(): ?string;

    /**
     * @param string $username
     */
    public function setUsername(string $username): void;

    /**
     * @return null|string
     */
    public function getFirstName(): ?string;

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void;

    /**
     * @return null|string
     */
    public function getSurname(): ?string;

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void;

    /**
     * @return null|string
     */
    public function getEmail(): ?string;

    /**
     * @param string $email
     */
    public function setEmail(string $email): void;

    /**
     * @return null|string
     */
    public function getPlainPassword(): ?string;

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void;

}
