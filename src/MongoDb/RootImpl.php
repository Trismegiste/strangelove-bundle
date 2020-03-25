<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

use MongoDB\BSON\ObjectIdInterface;

/**
 * 
 */
trait RootImpl {

    protected $_id;

    public function getPk(): ObjectIdInterface {
        return $this->_id;
    }

}
