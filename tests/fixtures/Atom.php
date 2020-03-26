<?php

/*
 * Toolbox
 */

namespace Tests\Fixtures;

use Trismegiste\Toolbox\MongoDb\Root;
use Trismegiste\Toolbox\MongoDb\RootImpl;

/**
 * Description of Atom
 */
class Atom implements Root {

    use RootImpl;

    protected $nucleus;
    protected $electron;
    protected $name;

    public function __construct(string $name, Nucleus $nucl, array $electron) {
        $this->nucleus = $nucl;
        $this->electron = $electron;
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function isIonized() {
        return count($this->electron) != $this->nucleus->getElectricCharge();
    }

    public function addElectron(Lepton $elec) {
        array_push($this->electron, $elec);
    }

    public function looseElectron(): Lepton {
        return array_pop($this->electron);
    }

    public function getAtomicNumber(): int {
        return $this->nucleus->getAtomicNumber();
    }

}
