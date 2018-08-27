<?php

namespace App\Model\Tax;

/**
 * Class PLTax
 * @package App\Model\Tax
 */
class PLTax implements TaxInterface
{
    /**
     * @return int
     */
    public function getTax(): int
    {
        return 23;
    }
}
