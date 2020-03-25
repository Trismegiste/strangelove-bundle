<?php

/*
 * Toolbox
 */

namespace Tests\Fixtures;

/**
 * Description of Hadron
 */
class Hadron extends Elementary {

    protected $quark;

    public function __construct(string $name, array $quark) {
        parent::__construct($name);
        $this->quark = $quark;
    }

    public function getQuark(): array {
        return new \ArrayIterator($this->quark);
    }

}
