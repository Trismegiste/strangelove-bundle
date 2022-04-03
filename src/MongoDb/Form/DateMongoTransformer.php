<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Trismegiste\Toolbox\MongoDb\Type\BsonDateTime;

/**
 * Transform a PHP DateTime into MongoDateTime
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
