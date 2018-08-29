<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="price_method")
 */
class PriceMethod
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="not.blank.fields")
     */
    private $priceMethodId;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return PriceMethod|null
     */
    public function getPriceMethodId(): ?PriceMethod
    {
        return $this->priceMethodId;
    }

    /**
     * @param PriceMethod $priceMethodId
     */
    public function setPriceMethodId(PriceMethod $priceMethodId): void
    {
        $this->priceMethodId = $priceMethodId;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
