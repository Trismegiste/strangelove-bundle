<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb\Type;

use DateTime;
use DateTimeZone;
use MongoDB\BSON\Persistable;
use MongoDB\BSON\UTCDateTime;

/**
 * DateTime wrapper
 */
class MongoDateTime implements Persistable
{

    protected $phpDate;

    public function __construct(DateTime $d)
    {
        $this->phpDate = clone $d;
    }

    public function bsonSerialize(): array
    {
        return [
            'utc' => new UTCDateTime($this->phpDate),
            'tz' => $this->phpDate->getTimezone()->getName(),
            'atom' => $this->phpDate->format(DateTime::ATOM)
        ];
    }

    public function bsonUnserialize(array $data): void
    {
        $this->phpDate = $data['utc']->toDateTime();
        $this->phpDate->setTimezone(new DateTimeZone($data['tz']));
    }

    public function getDateTime(): DateTime
    {
        return $this->phpDate;
    }

}
