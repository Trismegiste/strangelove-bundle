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
    
    public function __construct() {
        ;
    }
}
