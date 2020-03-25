<?php

/*
 * Toolbox
 */

namespace Tests\Fixtures;

use Trismegiste\Toolbox\MongoDb\Root;
use Trismegiste\Toolbox\MongoDb\RootImpl;

class Elementary implements Root {

    use RootImpl;

    protected $name;

    public function __construct(string $n) {
        $this->name = $n;
    }

}
