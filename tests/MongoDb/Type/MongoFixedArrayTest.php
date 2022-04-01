<?php

/*
 * Toolbox
 */

class MongoFixedArrayTest extends \PHPUnit\Framework\TestCase
{

    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new \Trismegiste\Toolbox\MongoDb\Type\MongoFixedArray();
    }

    public function testEmpty()
    {
        $this->assertCount(0, $this->sut);
    }

    public function testSerialize()
    {
        $this->sut->setSize(256);
        $this->sut[255] = 6.62;

        $dump = \MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($this->sut));
        $this->assertJson($dump);

        return $dump;
    }

    /** @depends testSerialize */
    public function testUnserialize(string $json)
    {
        $obj = \MongoDB\BSON\toPHP(MongoDB\BSON\fromJSON($json));

        $this->assertCount(256, $obj);
        $this->assertEquals(6.62, $obj[255]);
    }

}
