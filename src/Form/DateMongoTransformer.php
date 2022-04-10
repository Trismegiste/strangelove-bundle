<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\MongoDb\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Trismegiste\Strangelove\MongoDb\Type\BsonDateTime;

/**
 * Transform a PHP DateTime into BsonDateTime
 */
class DateMongoTransformer implements DataTransformerInterface
{

    public function reverseTransform($value)
    {
        return empty($value) ? null : new BsonDateTime($value);
    }

    public function transform($value)
    {
        return empty($value) ? null : $value->getDateTime();
    }

}
