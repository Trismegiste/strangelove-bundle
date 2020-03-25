<?php

/*
 * Toolbox
 */

use MongoDB\BSON\ObjectId;
use Tests\Fixtures\Hadron;
use Tests\Fixtures\Lepton;
use Tests\Fixtures\Quark;
use Tests\Toolbox\MongoDb\MongoTestable;

class PersistableImplTest extends MongoTestable {

    public function testDefaultWithoutRoot() {
        $obj = $this->resetWriteAndRead(new Lepton("muon"));
        $this->assertInstanceOf(ObjectId::class, $obj->_id);
        $this->assertEquals('muon', $obj->getName());
    }

    public function testAggregateWithoutRoot() {
        $doc = new Hadron("proton", [
            new Quark("up", 2 / 3),
            new Quark("up", 2 / 3),
            new Quark("down", -1 / 3)
        ]);

        $fromDb = $this->resetWriteAndRead($doc);
        $this->assertEquals("proton", $fromDb->getName());
        $this->assertEquals(1.0, $fromDb->getElectricCharge());
        $this->assertValidMongoId($fromDb->_id);
    }

}
