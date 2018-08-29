<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(
     *     max=1024,
     *     maxMessage = "Other information max length in orders is {{ limit }}."
     * )
     */
    private $otherInformation;

    /**
     * @ORM\Column(type="string", length=24, name="first_name")
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      min=3,
     *      max=24,
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      min=4,
     *      max=32,
     *      minMessage="min.length {{limit}}",
     *      maxMessage="max.length {{limit}}",
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=6)
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^([0-9]){2,2}-([0-9]){3,3}$/",
     *     message="Given incorrect post code address",
     * )
     */
    private $postCode;

    /**
     * @ORM\Column(type="integer", length=9)
     * @Assert\NotBlank(message="not.black.fields")
     * @Assert\Regex(
     *     pattern="/^[0-9]{9,9}$/",
     *     message="Given incorrect phone number",
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Length(
     *      max=64,
     *      maxMessage="max.length {{limit}}",
     * )
     * @Assert\Email(
     *     message = "incorrect data provided",
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^([A-ZĄĆĘŁŃÓŚŹŻ|a-ząćęłnóśźż| |\-|]){3,64}$/",
     *     message="Given city is incorrect",
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string",length=64)
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^([A-ZĄĆĘŁŃÓŚŹŻ|a-ząćęłnóśźż| |\-|]){3,64}$/",
     *     message="Given street is incorrect",
     * )
     */
    private $street;

    /**
     * @ORM\Column(type="string",length=8)
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^([0-9|A-Z|a-z|\/|\\]){1,5}$/",
     *     message="Given house number is incorrect",
     * )
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
     * @Assert\Valid()
     */
    private $priceMethods;

    /**
     * @Assert\Valid()
     */
    private $deliveryMethods;

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
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null|string
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param null|string $surname
     */
    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return null|string
     */
    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    /**
     * @param null|string $postCode
     */
    public function setPostCode(?string $postCode)
    {
        $this->postCode = $postCode;
    }

    /**
     * @return int|null
     */
    public function getPhone(): ?int
    {
        return $this->phone;
    }

    /**
     * @param int|null $phone
     */
    public function setPhone(?int $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     */
    public function setCity(?string $city)
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param null|string $street
     */
    public function setStreet(?string $street)
    {
        $this->street = $street;
    }

    /**
     * @return null|string
     */
    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    /**
     * @param null|string $houseNumber
     */
    public function setHouseNumber(?string $houseNumber)
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

    /**
     * @return array
     */
    public function getDeliveryMethods(): array
    {
        if (empty($this->deliveryMethods)) {
            return [new DeliveryMethod()];
        }

        return $this->deliveryMethods;
    }

    /**
     * @param array $deliveryMethods
     */
    public function setDeliveryMethods(array $deliveryMethods): void
    {
        $this->deliveryMethods = $deliveryMethods;
    }

    /**
     * @return array
     */
    public function getPriceMethods(): array
    {
        if (empty($this->priceMethods)) {
            return [new PriceMethod()];
        }

        return $this->priceMethods;
    }

    /**
     * @param array $priceMethods
     */
    public function setPriceMethods(array $priceMethods): void
    {
        $this->priceMethods = $priceMethods;
    }
}
