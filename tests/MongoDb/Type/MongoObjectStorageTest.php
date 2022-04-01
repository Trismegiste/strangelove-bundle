<?php

/*
 * Toolbox
 */

class MongoObjectStorageTest extends \PHPUnit\Framework\TestCase
{

    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new Trismegiste\Toolbox\MongoDb\Type\MongoObjectStorage();
    }

    public function testEmpty()
    {
        $this->assertCount(0, $this->sut);
    }

    public function testOneItem()
    {
        $obj = new \stdClass();
        $this->sut[$obj] = 123;
        $this->assertCount(1, $this->sut);
        $this->assertEquals(123, $this->sut[$obj]);
    }

    public function testSerialize()
    {
        $obj = new \stdClass();
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

}
