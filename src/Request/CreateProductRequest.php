<?php


namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint\UniqueField;

/**
 * Class CreateProductRequest
 * @package App\Request
 * @UniqueField(entityClass="App\Entity\Product", field="name", checkId=false)
 */
class CreateProductRequest
{
    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      min=6,
     *      max=128,
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     * @Assert\Regex(
     *     pattern="/^([A-ZĄĆĘŁŃÓŚŹŻ|a-ząćęłnóśźż|0-9|-| |.|,]){3,128}$/",
     *     message="incorrect data provided"
     * )
     */
    private $name;

    /**
     * @Assert\NotBlank(message="not.blank.fields")
     */
    private $price;

    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^[0-9]{1,9}$/",
     *     message="incorrect data provided"
     * )
     */
    private $magazine;

    private $description;

    /**
     * @Assert\Valid()
     */
    private $uploadProductImage;

    /**
     * @Assert\Valid()
     */
    private $categories;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int|null
     */
    public function getMagazine(): ?int
    {
        return $this->magazine;
    }

    /**
     * @param int $magazine
     */
    public function setMagazine(int $magazine): void
    {
        $this->magazine = $magazine;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getUploadProductImage(): array
    {
        if (empty($this->uploadProductImage)) {
            return [new UploadProductImageRequest()];
        } else {
            return $this->uploadProductImage;
        }
    }

    /**
     * @param array $uploadProductImage
     */
    public function setUploadProductImage(array $uploadProductImage): void
    {
        $this->uploadProductImage = $uploadProductImage;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        if (empty($this->categories)) {
            return [new SelectCategoriesRequest()];
        } else {
            return $this->categories;
        }
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }
}
