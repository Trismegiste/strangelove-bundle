<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

/**
 * Implementation of Persistable
 */
trait PersistableImpl
{

    public function bsonSerialize()
    {
        $ret = [];
        foreach (get_object_vars($this) as $key => $val) {
            if (('_id' === $key) && is_null($val)) {
                continue;
            }
            if (is_object($val)) {
                switch (get_class($val)) {
                    case \DateTime::class :
                        $final = new \MongoDB\BSON\UTCDateTime($val);
                        break;
                    default:
                        $final = $val;
                }
            } else {
                $final = $val;
            }

            $ret[$key] = $final;
        }

        return $ret;
    }

    public function bsonUnserialize(array $data): void
    {
        unset($data['__pclass']);
        foreach ($data as $key => $val) {
            if (is_object($val)) {
                switch (get_class($val)) {
                    case \MongoDB\BSON\UTCDateTime::class :
                        $final = $val->toDateTime();
                        $final->setTimezone(new \DateTimeZone(date_default_timezone_get()));
                        break;
                    case \stdClass::class :
                        $final = (array) $val;
                        break;
                    default:
                        $final = $val;
                }
            } else {
                $final = $val;
            }

            $this->$key = $final;
        }
    }

}
