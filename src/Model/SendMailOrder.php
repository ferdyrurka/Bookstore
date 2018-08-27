<?php


namespace App\Model;

/**
 * Class SendMailOrder
 * @package App\Model
 */
class SendMailOrder implements SendMailInterface
{
    /**
     * @var string
     */
    private $from = 'kontakt@lukaszstaniszewski.pl';

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $pathTemplate = 'email/order.html.twig';

    /**
     * @var string
     */
    private $subject = 'Zamówienie w sklepie';

    /**
     * @var array|null
     */
    private $attributes;

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getPathTemplate(): string
    {
        return $this->pathTemplate;
    }

    /**
     * @param string $pathTemplate
     */
    public function setPathTemplate(string $pathTemplate): void
    {
        $this->pathTemplate = $pathTemplate;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }


    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }
}
