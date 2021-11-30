<?php

namespace Tests\Fixtures;

use Trismegiste\Toolbox\MongoDb\Root;
use Trismegiste\Toolbox\MongoDb\RootImpl;

/**
 * Fixtures
 */
class Product implements Root
{

    use RootImpl;

    public $counter = 0;

}
