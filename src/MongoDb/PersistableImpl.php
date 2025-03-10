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

    public function bsonSerialize(): array
    {
        $this->beforeSave();

        $ret = get_object_vars($this);

        if (array_key_exists('_id', $ret) && is_null($ret['_id'])) {
            unset($ret['_id']);
        }

        return $ret;
    }

    protected function recursiveStdClass2Array(array &$arr): void
    {
        array_walk($arr, function (&$value) {
            if (is_object($value) && get_class($value) === 'stdClass') {
                $value = (array) $value;
            }
            if (is_array($value)) {
                $this->recursiveStdClass2Array($value);
            }
        });
    }

    public function bsonUnserialize(array $data): void
    {
        unset($data['__pclass']);

        $this->recursiveStdClass2Array($data);

        foreach ($data as $key => $val) {
            $this->$key = $val;
        }

        $this->afterLoad();
    }
}
