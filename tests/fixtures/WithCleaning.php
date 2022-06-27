<?php

/*
 * Strangelove
 */

namespace Tests\Fixtures;

class WithCleaning implements \MongoDB\BSON\Persistable
{

    use \Trismegiste\Strangelove\MongoDb\PersistableImpl;

    protected $timestamp;
    protected $saveCounter = 0;
    public $loadCounter = 0;

    protected function beforeSave(): void
    {
        $this->saveCounter++;
        $this->timestamp = new \MongoDB\BSON\UTCDateTime();
    }

    protected function afterLoad(): void
    {
        $this->loadCounter++;
    }

}
