<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @UniqueEntity("name")
 */
class Category
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", length=11)
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      min="3",
     *      max="64",
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     * @Assert\Regex(
     *     pattern="/^([A-ZĄĆĘŁŃÓŚŹŻ|a-ząćęłnóśźż| |-|&|0-9|.|,]){3,64}$/",
     *     message="incorrect data provided",
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      max="128"
     * )
     */
    private $description;

    /**
     * @Gedmo\Slug(fields={"name"}, separator="-",unique=true, style="camel")
     * @ORM\Column(length=128, type="string", unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="categoryReferences")
     */
    private $productReferences;

    /**
     * @return int|null
     */
    public function getId() :?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName() :?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return Collection
     */
    public function getProductReferences(): Collection
    {
        return $this->productReferences;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return isset($this->name) ? $this->name : '';
    }
}
