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

    use PersistableImpl;

    protected $_id;

    public function getPk(): ObjectIdInterface {
        return $this->_id;
    }

    public function setPk(ObjectIdInterface $pk): void {
        if (!is_null($this->_id)) {
            throw new LogicException('You cannot override an existing PK');
        }
        $this->_id = $pk;
    }

    public function isNew(): bool {
        return is_null($this->_id);
    }

}
