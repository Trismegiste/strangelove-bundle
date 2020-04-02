<?php

/*
 * Toolbox
 */

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use Trismegiste\Toolbox\MongoDb\PersistableImpl;

/**
 * Description of Internal
 */
class Internal implements Persistable
{

    use PersistableImpl;
    public $dob;
    public $arr;
}
