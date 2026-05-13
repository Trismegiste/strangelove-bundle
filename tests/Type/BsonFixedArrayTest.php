<?php

/*
 * Strangelove
 */

use MongoDB\BSON\Document;
use PHPUnit\Framework\TestCase;
use Trismegiste\Strangelove\Type\BsonFixedArray;

class BsonFixedArrayTest extends TestCase
{
    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new BsonFixedArray();
    }

    public function testEmpty()
    {
        $this->assertCount(0, $this->sut);
    }

    public function testSerialize()
    {
        $this->sut->setSize(256);
        $this->sut[255] = 6.62;

        $dump = Document::fromPHP($this->sut)->toRelaxedExtendedJSON();
        $this->assertJson($dump);

        return $dump;
    }

    /** @depends testSerialize */
    public function testUnserialize(string $json)
    {
        $obj = Document::fromJSON($json)->toPHP();

        $this->assertInstanceOf(SplFixedArray::class, $obj);
        $this->assertCount(256, $obj);
        $this->assertEquals(6.62, $obj[255]);
    }
}
