<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

/**
 * Magic overload for querying
 */
trait MagicRepository {

    public function __call($name, $arguments) {
        throw new LogicException('Not yet implemented');
    }

}
