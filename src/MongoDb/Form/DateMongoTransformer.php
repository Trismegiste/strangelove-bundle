<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Trismegiste\Toolbox\MongoDb\Type\MongoDateTime;

/**
 * Transform a PHP DateTime into MongoDateTime
 */
class DateMongoTransformer implements DataTransformerInterface
{

    public function reverseTransform($value)
    {
        return empty($value) ? null : new MongoDateTime($value);
    }

    public function transform($value)
    {
        return empty($value) ? null : $value->getDateTime();
    }

}
