<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="product")
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
     */
    private $name;

    /**
     * @ORM\Column(type="integer", length=32)
     */
    private $price;

    /**
     * @var float
     */
    private $priceFloat;

    /**
     * @ORM\Column(type="integer",length=11, unique=true, nullable=true, name="product_image_id")
     */
    private $productImageId;

    /**
     * @ORM\Column(type="integer",length=11)
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
     * @return float
     */
    public function getPriceFloat(): float
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
     * @return int
     */
    public function getMagazine(): int
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
     * @return string
     */
    public function getSlug(): string
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
     * @return string
     */
    public function getCreatedAt(): string
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
}
