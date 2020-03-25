<?php

/*
 * Toolbox
 */

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use Trismegiste\Toolbox\MongoDb\PersistableImpl;

/**
 * Description of Nucleus
 */
class Nucleus implements Persistable {

    use PersistableImpl;

    public function __construct(array $baryon) {
        ;
    }

}
