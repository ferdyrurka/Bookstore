<?php


namespace App\Request;

use App\Entity\PriceMethod;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PriceMethodRequest
 * @package App\Request
 */
class PriceMethodRequest
{
    /**
     * @Assert\NotBlank(message="not.blank.fields")
     */
    private $priceMethodId;

    /**
     * @return PriceMethod|null
     */
    public function getPriceMethodId(): ?PriceMethod
    {
        return $this->priceMethodId;
    }

    /**
     * @param PriceMethod $priceMethodId
     */
    public function setPriceMethodId(PriceMethod $priceMethodId): void
    {
        $this->priceMethodId = $priceMethodId;
    }
}
