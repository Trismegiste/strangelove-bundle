<?php

/*
 * Strangelove
 */

namespace Tests\Strangelove\MongoDb;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use PHPUnit\Framework\TestCase;

/**
 * MongoTestable contains common initialization and assertions for MongoDB
 */
class MongoTestable extends TestCase
{

    const collection = 'trismegiste_toolbox.collection';

    use MongoCheck;

    protected $mongo;

    protected function setUp(): void
    {
        $this->mongo = new Manager('mongodb://localhost:27017');
        $this->ping($this->mongo, 'trismegiste_toolbox');
    }

    protected function resetWriteAndRead(object $obj): object
    {
        $bulk = new BulkWrite(['ordered' => true]);
        $bulk->delete([]);
        $bulk->insert($obj);
        $this->mongo->executeBulkWrite(self::collection, $bulk);
        $cursor = $this->mongo->executeQuery(self::collection, new Query([]));
        $rows = iterator_to_array($cursor);
        $this->assertCount(1, $rows);
        $this->assertInstanceOf(get_class($obj), $rows[0]);

        return $rows[0];
    }

    protected function assertValidMongoId(string $pk)
    {
        $this->assertMatchesRegularExpression('/^[a-f0-9]{24}$/', $pk);
    }

}
