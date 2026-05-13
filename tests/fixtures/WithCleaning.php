<?php

/*
 * Strangelove
 */

namespace Tests\Fixtures;

use MongoDB\BSON\Persistable;
use MongoDB\BSON\UTCDateTime;
use Trismegiste\Strangelove\MongoDb\PersistableImpl;

class WithCleaning implements Persistable
{

    use PersistableImpl;

    protected UTCDateTime $timestamp;
    protected int $saveCounter = 0;
    public int $loadCounter = 0;

    protected function beforeSave(): void
    {
        $this->saveCounter++;
        $this->timestamp = new UTCDateTime();
    }

    protected function afterLoad(): void
    {
        $this->loadCounter++;
    }
}
