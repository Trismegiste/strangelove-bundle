<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

use MongoDB\BSON\ObjectIdInterface;
use MongoDB\BSON\Persistable;

/**
 * A Persistable for MongoDb with a MongoId
 */
interface Root extends Persistable {

    public function getPk(): ObjectIdInterface;
}
