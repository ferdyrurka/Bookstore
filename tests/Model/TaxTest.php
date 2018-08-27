<?php

namespace App\Tests\Model;

use App\Model\Tax;
use PHPUnit\Framework\TestCase;
use \Exception;

/**
 * Class TaxTest
 * @package App\Tests\Model
 */
class TaxTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testTax(): void
    {
        $tax = new Tax();
        $tax->setPricesWithTax('pl', 10.00);

        $this->assertEquals(12.3, $tax->getPriceWithTax());

        $this->expectException(Exception::class);
        $tax->setPricesWithTax('en', 10.00);
    }
}
