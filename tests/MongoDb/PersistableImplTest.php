<?php

/*
 * Toolbox
 */

use MongoDB\BSON\ObjectId;
use Tests\Fixtures\Employee;
use Tests\Fixtures\Hadron;
use Tests\Fixtures\Internal;
use Tests\Fixtures\Lepton;
use Tests\Fixtures\Nucleus;
use Tests\Fixtures\Quark;
use Tests\Fixtures\Vector;
use Tests\Toolbox\MongoDb\MongoTestable;
use Trismegiste\Toolbox\MongoDb\Type\BsonDateTime;
use Trismegiste\Toolbox\MongoDb\Type\MongoDateTime;

class PersistableImplTest extends MongoTestable
{

    public function testDefaultWithoutRoot()
    {
        $obj = $this->resetWriteAndRead(new Lepton("muon"));
        $this->assertInstanceOf(ObjectId::class, $obj->_id);
        $this->assertEquals('muon', $obj->getName());
    }

    public function testAggregateWithoutRoot()
    {
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

    public function testComplexWithoutRoot()
    {
        $up = new Quark('up', 2 / 3);
        $down = new Quark('down', -1 / 3);
        $proton = new Hadron('proton', [$up, $up, $down]);
        $neutron = new Hadron('neutron', [$up, $down, $down]);
        $helion = new Nucleus([$proton, $proton, $neutron, $neutron]); // note : this is reference

        $fromDb = $this->resetWriteAndRead($helion);  // note : this is copy
        $this->assertEquals(2.0, $fromDb->getElectricCharge());
        $this->assertEquals(2, $fromDb->getAtomicNumber());
        $this->assertValidMongoId($fromDb->_id);
    }

    public function testMongoType()
    {
        $obj = new Internal();
        $obj->_id = new ObjectId();
        $obj->dob = new BsonDateTime("1993-07-07");
        $obj->arr = ['data' => 42];
        $fromDb = $this->resetWriteAndRead($obj);
        $this->assertEquals($obj, $fromDb);
    }

    public function testArray()
    {
        $obj = new Vector();
        $obj->setContent(['date' => new BsonDateTime()]);
        $fromDb = $this->resetWriteAndRead($obj);
        $restored = $fromDb->getContent();
        $this->assertArrayHasKey('date', $restored);
        $this->assertInstanceOf(BsonDateTime::class, $restored['date']);
    }

    public function testDumpWhenPersistableIsSubclassed()
    {
        $obj = new Employee();
        $obj->name = 'Feyd'; // from Person
        $obj->salary = 42;

        $dump = $obj->bsonSerialize();
        $this->assertArrayHasKey('name', $dump);
        $this->assertArrayHasKey('salary', $dump);
        $this->assertEquals(42, $dump['salary']);
        $this->assertEquals('Feyd', $dump['name']);
    }

}
