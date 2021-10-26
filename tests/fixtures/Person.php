<?php

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use Trismegiste\Toolbox\MongoDb\PersistableImpl;

class Person implements Persistable
{

    use PersistableImpl;

    public $name;

}
