<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb\Type;

use MongoDB\BSON\Persistable;
use MongoDB\BSON\UTCDateTime;

/**
 * DateTime wrapper since we cannot inherit from DateTime or implement the interface DateTimeInterface
 */
class MongoDateTime implements Persistable
{

    protected $phpDate;

    public function __construct($d = 'now')
    {
        if ($d instanceof \DateTimeInterface) {
            $this->phpDate = clone $d;
        } else {
            $this->phpDate = new \DateTime($d);
        }
    }

    public function bsonSerialize(): array
    {
        return [
            'utc' => new UTCDateTime($this->phpDate),
            'tz' => $this->phpDate->getTimezone()->getName(),
            'atom' => $this->phpDate->format(\DateTime::ATOM)   // sometime this field is simpler for query
        ];
    }

    public function bsonUnserialize(array $data): void
    {
        $this->phpDate = $data['utc']->toDateTime();
        $this->phpDate->setTimezone(new \DateTimeZone($data['tz']));
    }

    public function getDateTime(): \DateTime
    {
        return $this->phpDate;
    }

    public function format(string $format): string
    {
        return $this->phpDate->format($format);
    }

    public function __toString(): string
    {
        return $this->phpDate->format(\DateTime::ATOM);
    }

}
