<?php

/*
 * Strangelove
 */

namespace Tests\Strangelove\MongoDb;

use LogicException;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;
use Tests\Fixtures\AtomBuilder;

/**
 * RootImplTest tests for RootImpl Trait
 */
class RootImplTest extends MongoTestable
{

    use AtomBuilder;

    public function testComplexWithRoot()
    {
        $atom = $this->createAtom('He', 2, 4);
        $atom->setPk(new ObjectId());

        $fromDb = $this->resetWriteAndRead($atom);
        $this->assertFalse($fromDb->isIonized());
        $this->assertValidMongoId($fromDb->getPk());
        $this->assertEquals($atom, $fromDb);

        $fromDb->looseElectron();
        $bulk = new BulkWrite();
        $bulk->update(['_id' => $fromDb->getPk()], $fromDb);
        $this->mongo->executeBulkWrite(self::collection, $bulk);

        $cursor = $this->mongo->executeQuery(self::collection, new Query([]));
        $fromDb = iterator_to_array($cursor);
        $this->assertCount(1, $fromDb);

        $ion = $fromDb[0];
        $this->assertTrue($ion->isIonized());

        return $ion->getPk();
    }

    public function testCannotChangePrimaryKey()
    {
        $this->expectException(LogicException::class);
        $atom = $this->createAtom('H', 1, 1);
        $atom->setPk(new ObjectId());
        $atom->setPk(new ObjectId());
    }

    public function testCloneNoPrimaryKey()
    {
        $cursor = $this->mongo->executeQuery(self::collection, new Query([]));
        $first = iterator_to_array($cursor)[0];
        $this->assertFalse($first->isNew());
        $copy = clone $first;
        $this->assertTrue($copy->isNew());
    }

}
