<?php

/*
 * Strangelove
 */

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use Trismegiste\Strangelove\MongoDb\PersistableImpl;

/**
 * Description of Lepton
 */
class Lepton extends Elementary implements Persistable {

    use PersistableImpl;
}
