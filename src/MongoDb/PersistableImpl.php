<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\MongoDb;

/**
 * Implementation of Persistable
 */
trait PersistableImpl
{

    protected function beforeSave(): void
    {
        
    }

    protected function afterLoad(): void
    {
        
    }

    public function bsonSerialize()
    {
        $this->beforeSave();

        $ret = get_object_vars($this);

        if (array_key_exists('_id', $ret) && is_null($ret['_id'])) {
            unset($ret['_id']);
        }

        return $ret;
    }

    public function bsonUnserialize(array $data): void
    {
        unset($data['__pclass']);

        foreach ($data as $key => $val) {
            // forced conversion of stdClass into arrays
            if (is_object($val) && get_class($val) === 'stdClass') {
                $this->$key = (array) $val;
            } else {
                $this->$key = $val;
            }
        }

        $this->afterLoad();
    }

}
