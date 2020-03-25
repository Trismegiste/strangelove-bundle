<?php

/*
 * Toolbox
 */

use MongoDB\BSON\ObjectId;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Elementary;

class RootTest extends TestCase {

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

    public function testSimple() {
        $obj = $this->resetWriteAndRead(new Elementary("quark top"));
        $this->assertInstanceOf(ObjectId::class, $obj->getPk());
      //  print_r($obj);
    }

}
