<?php

/*
 * Strangelove
 */

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use Trismegiste\Strangelove\MongoDb\PersistableImpl;

/**
 * Description of Internal
 */
class Internal implements Persistable
{

    use PersistableImpl;
    public $dob;
    public $arr;
}
