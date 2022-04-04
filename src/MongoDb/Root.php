<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\MongoDb;

use MongoDB\BSON\ObjectIdInterface;
use MongoDB\BSON\Persistable;

/**
 * A Persistable for MongoDb with a MongoId
 */
interface Root extends Persistable
{

    public function getPk(): ObjectIdInterface;

    public function setPk(ObjectIdInterface $pk): void;

    public function isNew(): bool;
}
