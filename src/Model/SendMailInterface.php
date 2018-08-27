<?php


namespace App\Model;

/**
 * Interface SendMailInterface
 * @package App\Model
 */
interface SendMailInterface
{
    /**
     * @return string
     */
    public function getFrom(): string;

    /**
     * @param string $from
     */
    public function setFrom(string $from): void;

    /**
     * @param string $pathTemplate
     */
    public function setPathTemplate(string $pathTemplate): void;

    /**
     * @return string
     */
    public function getPathTemplate(): string;

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void;

    /**
     * @return string
     */
    public function getSubject(): string;

    /**
     * @param string $to
     */
    public function setTo(string $to): void;

    /**
     * @return string
     */
    public function getTo(): string;

    /**
     * @param array $attr
     */
    public function setAttributes(array $attr): void;

    /**
     * @return array|null
     */
    public function getAttributes(): ?array;
}
