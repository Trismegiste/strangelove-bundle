<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

/**
 * Implementation of Persistable
 */
trait PersistableImpl {

    public function bsonSerialize() {
        $ret = [];
        foreach ($this as $key => $val) {
            if (('_id' === $key) && is_null($val)) {
                continue;
            }
            $ret[$key] = $val;
        }

        return $ret;
    }

    public function bsonUnserialize(array $data): void {
        unset($data['__pclass']);
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }

}
