<?php

namespace App\Model\Tax;

interface TaxInterface
{
    public function getTax() : int;
}