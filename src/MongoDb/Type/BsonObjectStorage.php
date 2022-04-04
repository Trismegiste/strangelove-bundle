<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\MongoDb\Type;

/**
 * SplObjectStorage replacement
 */
class BsonObjectStorage extends \SplObjectStorage implements \MongoDB\BSON\Persistable
{

    public function bsonSerialize(): array
    {
        $ret = [];
        for ($this->rewind(); $this->valid(); $this->next()) {
            $ret[] = [
                'obj' => $this->current(),
                'info' => $this->getInfo()
            ];
        }

        return ['content' => $ret];
    }

    public function bsonUnserialize(array $data): void
    {
        foreach ($data['content'] as $pair) {
            $this[$pair->obj] = $pair->info;
        }
    }

}
