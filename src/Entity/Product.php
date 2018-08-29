<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="product")
 * @UniqueEntity("name")
 * @UniqueEntity("slug")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=128, unique=true)
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
     * @ORM\Column(type="integer", length=32)
     */
    private $price;

    /**
     * @var float
     * @Assert\NotBlank(message="not.blank.fields")
     */
    private $priceFloat;

    /**
     * @ORM\Column(type="integer",length=11, unique=true, nullable=true, name="product_image_id")
     */
    private $productImageId;

    /**
     * @ORM\Column(type="integer",length=11)
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^[0-9]{1,9}$/",
     *     message="incorrect data provided"
     * )
     */
    private $magazine;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-",unique=true)
     * @ORM\Column(length=128, type="string", unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="ProductImage", inversedBy="productReferences")
     * @ORM\JoinColumn(name="product_image_id", referencedColumnName="product_id")
     */
    private $productImageReferences;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="productReferences")
     * @ORM\JoinTable(name="product_category",
     *      joinColumns={
     *          @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *      },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *     }
     * )
     */
    private $categoryReferences;

    /**
     * @Assert\Valid()
     */
    private $uploadProductImage;

    private $categoriesId;

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
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price)
    {
        $this->price = $price;
    }

    /**
     * @return float|null
     */
    public function getPriceFloat(): ?float
    {
        return $this->priceFloat;
    }

    /**
     * @param float $priceFloat
     */
    public function setPriceFloat(float $priceFloat): void
    {
        $this->priceFloat = $priceFloat;
    }

    /**
     * @return int|null
     */
    public function getProductImageId(): ?int
    {
        return $this->productImageId;
    }

    /**
     * @param int $productImageId
     */
    public function setProductImageId(int $productImageId)
    {
        $this->productImageId = $productImageId;
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
    public function setMagazine(int $magazine)
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
    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return null|string
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Collection
     */
    public function getCategoryReferences(): Collection
    {
        return $this->categoryReferences;
    }

    /**
     * @param array|ArrayCollection $categoryReferences
     */
    public function setCategoryReferences($categoryReferences): void
    {
        $this->categoryReferences = $categoryReferences;
    }

    /**
     * @return ProductImage|null
     */
    public function getProductImageReferences(): ?ProductImage
    {
        return $this->productImageReferences;
    }

    /**
     * @param ProductImage $productImage
     */
    public function setProductImageReferences(ProductImage $productImage): void
    {
        $this->productImageReferences = $productImage;
    }

    /**
     * @return array
     */
    public function getUploadProductImage(): array
    {
        if (empty($this->uploadProductImage)) {
            return [new ProductImage()];
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
     * @return mixed
     */
    public function getCategoriesId()
    {
        return $this->categoriesId;
    }

    /**
     * @param mixed $categoriesId
     */
    public function setCategoriesId($categoriesId): void
    {
        $this->categoriesId = $categoriesId;
    }
}
