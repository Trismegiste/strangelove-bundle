<?php

namespace Tests\Fixtures;

use Trismegiste\Strangelove\MongoDb\Root;
use Trismegiste\Strangelove\MongoDb\RootImpl;

/**
 * Fixtures
 */
class Product implements Root
{

    use RootImpl;

    public $counter = 0;

}
