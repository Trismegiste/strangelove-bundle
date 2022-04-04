<?php

/*
 * Strangelove
 */

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use Trismegiste\Strangelove\MongoDb\PersistableImpl;

abstract class Elementary implements Persistable {

    use PersistableImpl;

    protected $name;

    public function __construct(string $n) {
        $this->name = $n;
    }

    public function getName() {
        return $this->name;
    }

}
