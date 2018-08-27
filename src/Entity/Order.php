<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $products;

    /**
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, name="user_id")
     */
    private $userId;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $deliveryMethodId;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $priceMethodId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $otherInformation;

    /**
     * @ORM\Column(type="string", length=24, name="first_name")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $postCode;

    /**
     * @ORM\Column(type="integer", length=9)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $city;

    /**
     * @ORM\Column(type="string",length=64)
     */
    private $street;

    /**
     * @ORM\Column(type="string",length=8)
     */
    private $houseNumber;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userReferences;

    /**
     * @ORM\ManyToOne(targetEntity="DeliveryMethod")
     * @ORM\JoinColumn(name="delivery_method_id", referencedColumnName="id")
     */
    private $deliveryMethodReferences;

    /**
     * @ORM\ManyToOne(targetEntity="PriceMethod")
     * @ORM\JoinColumn(name="price_method_id", referencedColumnName="id")
     */
    private $priceMethodReferences;

    /**
     * @return int
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     */
    public function setProducts(array $products)
    {
        $this->products = $products;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost(float $cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     */
    public function setUserId(?int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getDeliveryMethodId(): int
    {
        return $this->deliveryMethodId;
    }

    /**
     * @param int $deliveryMethodId
     */
    public function setDeliveryMethodId(int $deliveryMethodId)
    {
        $this->deliveryMethodId = $deliveryMethodId;
    }

    /**
     * @return int
     */
    public function getPriceMethodId(): int
    {
        return $this->priceMethodId;
    }

    /**
     * @param int $priceMethodId
     */
    public function setPriceMethodId(int $priceMethodId)
    {
        $this->priceMethodId = $priceMethodId;
    }

    /**
     * @return null|string
     */
    public function getOtherInformation(): ?string
    {
        return $this->otherInformation;
    }

    /**
     * @param null|string $otherInformation
     */
    public function setOtherInformation(?string $otherInformation)
    {
        $this->otherInformation = $otherInformation;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getPostCode(): string
    {
        return $this->postCode;
    }

    /**
     * @param string $postCode
     */
    public function setPostCode(string $postCode)
    {
        $this->postCode = $postCode;
    }

    /**
     * @return int
     */
    public function getPhone(): int
    {
        return $this->phone;
    }

    /**
     * @param int $phone
     */
    public function setPhone(int $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    /**
     * @param string $houseNumber
     */
    public function setHouseNumber(string $houseNumber)
    {
        $this->houseNumber = $houseNumber;
    }

    /**
     * @return User|null
     */
    public function getUserReferences(): ?User
    {
        return $this->userReferences;
    }

    /**
     * @param User|null $userReferences
     */
    public function setUserReferences(?User $userReferences)
    {
        $this->userReferences = $userReferences;
    }

    /**
     * @return DeliveryMethod
     */
    public function getDeliveryMethodReferences(): DeliveryMethod
    {
        return $this->deliveryMethodReferences;
    }

    /**
     * @param DeliveryMethod $deliveryMethod
     */
    public function setDeliveryMethodReferences(DeliveryMethod $deliveryMethod)
    {
        $this->deliveryMethodReferences = $deliveryMethod;
    }

    /**
     * @return PriceMethod
     */
    public function getPriceMethodReferences(): PriceMethod
    {
        return $this->priceMethodReferences;
    }

    /**
     * @param PriceMethod $priceMethod
     */
    public function setPriceMethodReferences(PriceMethod $priceMethod)
    {
        $this->priceMethodReferences = $priceMethod;
    }
}
