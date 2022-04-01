<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Toolbox\MongoDb\Type\MongoDateTime;
use Trismegiste\Toolbox\MongoDb\Type\MongoObjectStorage;

/*
 * Toolbox
 */

class MongoObjectStorageTest extends TestCase
{

    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new MongoObjectStorage();
    }

    public function testEmpty()
    {
        $this->assertCount(0, $this->sut);
    }

    public function testOneItem()
    {
        $obj = new stdClass();
        $this->sut[$obj] = 123;
        $this->assertCount(1, $this->sut);
        $this->assertEquals(123, $this->sut[$obj]);
    }

    public function testSerialize()
    {
        $obj = new stdClass();
        $this->sut[$obj] = 123;

        $dump = \MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($this->sut));
        $this->assertJson($dump);

        return $dump;
    }

    /** @depends testSerialize */
    public function testUnserialize(string $json)
    {
        $obj = \MongoDB\BSON\toPHP(MongoDB\BSON\fromJSON($json));

        $this->assertCount(1, $obj);
        $obj->rewind();
        $this->assertInstanceOf(stdClass::class, $obj->current());
        $this->assertEquals(123, $obj->getInfo());
    }

    public function testSerializeCombo()
    {
        $obj = new MongoDateTime('1997-12-25');
        $this->sut[$obj] = 123;

        $dump = \MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($this->sut));
        $this->assertJson($dump);

        return $dump;
    }

    /** @depends testSerializeCombo */
    public function testUnserializeCombo(string $json)
    {
        $obj = \MongoDB\BSON\toPHP(MongoDB\BSON\fromJSON($json));

        $this->assertCount(1, $obj);
        $obj->rewind();
        $this->assertInstanceOf(MongoDateTime::class, $obj->current());
        $this->assertEquals(123, $obj->getInfo());
        $this->assertEquals(1997, $obj->current()->format('Y'));
    }

}
