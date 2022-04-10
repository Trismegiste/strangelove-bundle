<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\Type;

/**
 * This is a SplFixedArray with BSON capabilities
 */
class BsonFixedArray extends \SplFixedArray implements \MongoDB\BSON\Persistable
{

    public function bsonSerialize(): array
    {
        return ['fixed' => $this->toArray()];
    }

    public function bsonUnserialize(array $data): void
    {
        $this->setSize(count($data['fixed']));
        foreach ($data['fixed'] as $key => $val) {
            $this[$key] = $val;
        }
    }

}
