<?php

/*
 * Strangelove
 */

namespace Tests\Fixtures;

/**
 * Description of Quark
 */
class Quark extends Elementary {

    protected $charge;

    public function __construct(string $n, float $electric) {
        parent::__construct($n);
        $this->charge = $electric;
    }

    public function getElectricCharge(): float {
        return $this->charge;
    }

}
