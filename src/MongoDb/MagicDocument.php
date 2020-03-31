<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

use ArrayAccess;
use InvalidArgumentException;
use LogicException;
use MongoDB\BSON\ObjectIdInterface;
use OutOfBoundsException;

/**
 * MagicDocument
 */
class MagicDocument implements ArrayAccess, Root
{

    protected $_id;
    protected $properties = [];

    public function getPk(): ObjectIdInterface
    {
        return $this->_id;
    }

    public function setPk(ObjectIdInterface $pk): void
    {
        if (!is_null($this->_id)) {
            throw new LogicException('You cannot override an existing PK');
        }
        $this->_id = $pk;
    }

    public function isNew(): bool
    {
        return is_null($this->_id);
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->properties);
    }

    public function offsetGet($offset)
    {
        if ($this->isNew() && !array_key_exists($offset, $this->properties)) {
            $val = null;
        } else {
            $val = $this->properties[$offset];
        }

        return $val;
    }

    public function offsetSet($offset, $value): void
    {
        if (empty($offset) || !is_string($offset)) {
            throw new InvalidArgumentException("Only a string makes a valid name for a property");
        }

        if ('_id' === $offset) {
            $this->setPk($value);
        } else {
            if (!$this->isNew() && !array_key_exists($offset, $this->properties)) {
                throw new OutOfBoundsException("Property '$offset' does not exist on this document");
            } else {
                $this->properties[$offset] = $value;
            }
        }
    }

    public function offsetUnset($offset): void
    {
        if ('_id' === $offset) {
            throw new LogicException("Cannot unset the primary key on this document");
        }
        unset($this->properties[$offset]);
    }

    public function bsonSerialize()
    {
        $flat = $this->properties;
        if (!is_null($this->_id)) {
            $flat['_id'] = $this->_id;
        }

        return $flat;
    }

    public function bsonUnserialize(array $data): void
    {
        $this->_id = $data['_id'];
        unset($data['_id']);
        unset($data['__pclass']);
        $this->properties = $data;
    }

}
