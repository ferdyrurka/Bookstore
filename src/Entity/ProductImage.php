<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_image")
 */
class ProductImage
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11, name="product_id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $productId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     */
    private $hashName;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $extension;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="productImageReferences")
     */
    private $productReferences;

    /**
     * @return int
     */
    public function getProductId() :int
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getPath() :string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path .= $path;
        $this->path = str_replace('../', '', $this->path);
    }

    /**
     * @return string
     */
    public function getHashName() :string
    {
        return $this->hashName;
    }

    /**
     * @param string $hashName
     */
    public function setHashName(string $hashName)
    {
        $hashName = htmlspecialchars($hashName);
        $hashName = hash('MD5', mt_rand(0, 9999).$hashName.mt_rand(0, 9999));

        $this->hashName = $hashName;
    }

    /**
     * @return string
     */
    public function getExtension() : string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension(string $extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return Product
     */
    public function getProductReferences(): Product
    {
        return $this->productReferences;
    }

    /**
     * @param Product $productReferences
     */
    public function setProductReferences(Product $productReferences)
    {
        $this->productReferences = $productReferences;
    }
}
