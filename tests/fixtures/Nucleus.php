<?php

/*
 * Strangelove
 */

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use Trismegiste\Strangelove\MongoDb\PersistableImpl;

/**
 * Description of Nucleus
 */
#[\AllowDynamicProperties]
class Nucleus implements Persistable, ElectricCharge {

    use PersistableImpl;

    protected $baryon;

    public function __construct(array $hadron) {
        $this->baryon = $hadron;
    }

    public function getAtomicNumber(): int {
        $cnt = 0;
        foreach ($this->baryon as $nucleon) {
            if ($nucleon->getName() === 'proton') {
                $cnt++;
            }
        }

        return $cnt;
    }

    public function getElectricCharge(): float {
        $charge = 0;
        foreach ($this->baryon as $nucleon) {
            $charge += $nucleon->getElectricCharge();
        }

        return $charge;
    }

}
