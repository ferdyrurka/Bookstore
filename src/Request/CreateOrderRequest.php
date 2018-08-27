<?php


namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreateOrderRequest
 * @package App\Request
 */
class CreateOrderRequest
{

    /**
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
     * @Assert\Length(
     *     max=1024,
     *     maxMessage = "Other information max length in orders is {{ limit }}."
     * )
     */
    private $otherInformation;

    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^([0-9]){2,2}-([0-9]){3,3}$/",
     *     message="Given incorrect post code address",
     * )
     */
    private $postCode;

    /**
     * @Assert\NotBlank(message="not.black.fields")
     * @Assert\Regex(
     *     pattern="/^[0-9]{9,9}$/",
     *     message="Given incorrect phone number",
     * )
     */
    private $phone;

    /**
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
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^([A-ZĄĆĘŁŃÓŚŹŻ|a-ząćęłnóśźż| |\-|]){3,64}$/",
     *     message="Given city is incorrect",
     * )
     */
    private $city;

    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^([A-ZĄĆĘŁŃÓŚŹŻ|a-ząćęłnóśźż| |\-|]){3,64}$/",
     *     message="Given street is incorrect",
     * )
     */
    private $street;

    /**
     * @Assert\NotBlank(message="not.blank.fields")
     * @Assert\Regex(
     *     pattern="/^([0-9|A-Z|a-z|\/|\\]){1,5}$/",
     *     message="Given house number is incorrect",
     * )
     */
    private $houseNumber;

    /**
     * @Assert\Valid()
     */
    private $priceMethods;

    /**
     * @Assert\Valid()
     */
    private $deliveryMethods;

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
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
     * @return null|string
     */
    public function getSurname(): ?string
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
     * @return null|string
     */
    public function getOtherInformation(): ?string
    {
        return $this->otherInformation;
    }

    /**
     * @param string $otherInformation
     */
    public function setOtherInformation(string $otherInformation): void
    {
        $this->otherInformation = $otherInformation;
    }

    /**
     * @return null|string
     */
    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    /**
     * @param string $postCode
     */
    public function setPostCode(string $postCode): void
    {
        $this->postCode = $postCode;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
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
     * @param string $email
     */
    public function setEmail(string $email): void
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
     * @param string $city
     */
    public function setCity(string $city): void
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
     * @param string $street
     */
    public function setStreet(string $street): void
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
     * @param string $houseNumber
     */
    public function setHouseNumber(string $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }

    /**
     * @return array
     */
    public function getDeliveryMethods(): array
    {
        if (empty($this->deliveryMethods)) {
            return [new DeliveryMethodRequest()];
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
            return [new PriceMethodRequest()];
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
