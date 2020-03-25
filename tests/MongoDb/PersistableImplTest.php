<?php

/*
 * Toolbox
 */

use MongoDB\BSON\ObjectId;
use Tests\Fixtures\Lepton;
use Tests\Toolbox\MongoDb\MongoTestable;

class PersistableImplTest extends MongoTestable {

    public function testDefaultWithoutRoot() {
        $obj = $this->resetWriteAndRead(new Lepton("muon"));
        $this->assertInstanceOf(ObjectId::class, $obj->_id);
        $this->assertEquals('muon', $obj->getName());
    }

}
