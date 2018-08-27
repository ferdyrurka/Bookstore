<?php


namespace App\Request;

use App\Entity\DeliveryMethod;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DeliveryMethodRequest
 * @package App\Request
 */
class DeliveryMethodRequest
{
    /**
     * @Assert\NotBlank(message="not.blank.fields")
     */
    private $deliveryMethodId;

    /**
     * @return DeliveryMethod|null
     */
    public function getDeliveryMethodId(): ?DeliveryMethod
    {
        return $this->deliveryMethodId;
    }

    /**
     * @param DeliveryMethod $deliveryMethodId
     */
    public function setDeliveryMethodId(DeliveryMethod $deliveryMethodId): void
    {
        $this->deliveryMethodId = $deliveryMethodId;
    }
}
