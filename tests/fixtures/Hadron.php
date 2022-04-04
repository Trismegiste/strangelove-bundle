<?php

/*
 * Strangelove
 */

namespace Tests\Fixtures;

/**
 * Description of Hadron
 */
class Hadron extends Elementary implements ElectricCharge {

    protected $quark;

    public function __construct(string $name, array $quark) {
        parent::__construct($name);
        $this->quark = $quark;
    }

    public function getQuark(): array {
        return new \ArrayIterator($this->quark);
    }

    public function getElectricCharge(): float {
        $sum = 0;
        foreach ($this->quark as $q) {
            $sum += $q->getElectricCharge();
        }

        return $sum;
    }

}
