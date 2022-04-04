<?php

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use Trismegiste\Strangelove\MongoDb\PersistableImpl;

class Person implements Persistable
{

    use PersistableImpl;

    public $name;

}
