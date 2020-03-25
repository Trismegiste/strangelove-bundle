<?php

/*
 * Toolbox
 */

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use Trismegiste\Toolbox\MongoDb\PersistableImpl;

/**
 * Description of Lepton
 */
class Lepton extends Elementary implements Persistable {

    use PersistableImpl;
    //put your code here
}
