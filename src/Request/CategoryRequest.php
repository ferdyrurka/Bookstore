<?php


namespace App\Request;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint\UniqueField;

/**
 * Class CategoryRequest
 * @package App\Request
 * @UniqueField(entityClass="App\Entity\Category", field="name")
 */
class CategoryRequest
{

    /**
     * @var int|null
     */
    private $id;

    /**
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
     * @Assert\Length(
     *      max="128",
     *      maxMessage="max.length {{limit}}",
     * )
     */
    private $description;

    /**
     * @param Category $category
     * @return CategoryRequest
     */
    public function setFormData(Category $category): self
    {
        $this->id = $category->getId();
        $this->setName($category->getName());
        $this->setDescription($category->getDescription());

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
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
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
