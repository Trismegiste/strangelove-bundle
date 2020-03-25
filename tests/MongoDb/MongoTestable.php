<?php

/*
 * Toolbox
 */

namespace Tests\Toolbox\MongoDb;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use PHPUnit\Framework\TestCase;

/**
 * Description of MongoTestable
 */
class MongoTestable extends TestCase {

    protected $mongo;

    protected function setUp(): void {
        $this->mongo = new Manager('mongodb://localhost:27017');
    }

    protected function resetWriteAndRead(object $obj): object {
        $bulk = new BulkWrite(['ordered' => true]);
        $bulk->delete([]);
        $bulk->insert($obj);
        $this->mongo->executeBulkWrite('testdb.collection', $bulk);
        $cursor = $this->mongo->executeQuery('testdb.collection', new Query([]));
        $rows = iterator_to_array($cursor);
        $this->assertCount(1, $rows);
        $this->assertInstanceOf(get_class($obj), $rows[0]);

        return $rows[0];
    }

    protected function assertValidMongoId(string $pk) {
        $this->assertRegExp('/^[a-f0-9]{24}$/', $pk);
    }

}
