<?php


namespace App\Request;

use App\Entity\Product;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint\UniqueField;

/**
 * Class UpdateProductRequest
 * @package App\Request
 * @UniqueField(entityClass="App\Entity\Product", field="name")
 */
class UpdateProductRequest
{
    /**
     * @var integer
     */
    private $id;

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
     * @Assert\GreaterThanOrEqual(
     *     value = 0
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
     * UpdateProductRequest constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->id = $product->getId();
        $this->setName($product->getName());
        $selectCategories = new SelectCategoriesRequest();
        $selectCategories->setCategoriesId($product->getCategoryReferences()->getValues());
        $this->setCategories([$selectCategories]);
        $this->setMagazine($product->getMagazine());
        $this->setDescription($product->getDescription());
        $this->setPrice($product->getPriceFloat());
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
    public function getName(): string
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
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getMagazine(): int
    {
        return $this->magazine;
    }

    /**
     * @param int|null $magazine
     */
    public function setMagazine(?int $magazine): void
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
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
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
}
