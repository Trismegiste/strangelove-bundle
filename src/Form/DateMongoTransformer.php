<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Trismegiste\Strangelove\Type\BsonDateTime;

/**
 * Transform a PHP DateTime into BsonDateTime
 */
class DateMongoTransformer implements DataTransformerInterface
{

    public function reverseTransform(mixed $value): mixed
    {
        return empty($value) ? null : new BsonDateTime($value);
    }

    public function transform(mixed $value): mixed
    {
        return empty($value) ? null : $value->getDateTime();
    }

}
