<?php

namespace App\Model;

use App\Model\Tax\PLTax;

/**
 * Class Tax
 * @package App\Model
 */
class Tax
{
    private $price;
    private $obj;

    /**
     * @param $locale
     * @param float $priceNoTax
     * @throws \Exception
     */
    public function setPricesWithTax($locale, float $priceNoTax): void
    {
        switch ($locale) {
            case 'pl':
                $locale = new PLTax();
                break;
            default:
                throw new \Exception('Undefined Tax');
                break;
        }

        $this->obj = $locale;

        $tax = (float) $this->obj->getTax() / 100;
        $tax = $priceNoTax * $tax;
        $price = $priceNoTax + $tax;

        $this->price = number_format($price, 2);
    }

    /**
     * @return mixed
     */
    public function getPriceWithTax() : float
    {
        return $this->price;
    }
}
