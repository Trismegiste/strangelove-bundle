<?php

/*
 * Strangelove
 */

use MongoDB\BSON\Document;
use PHPUnit\Framework\TestCase;
use Trismegiste\Strangelove\Type\BsonDateTime;

class BsonDateTimeTest extends TestCase
{
    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new BsonDateTime('2022-04-01 12:34:56', new DateTimeZone('Asia/Tokyo'));
    }

    public function testSerialize()
    {
        $dump = Document::fromPHP($this->sut)->toRelaxedExtendedJSON();
        $this->assertJson($dump);

        return $dump;
    }

    /** @depends testSerialize */
    public function testUnserialize(string $json)
    {
        $obj = Document::fromJSON($json)->toPHP();
        $this->assertInstanceOf(BsonDateTime::class, $obj);
        $this->assertEquals('2022-04-01T12:34:56+09:00', $obj->format(DateTime::ATOM));
    }

    /** @depends testSerialize */
    public function testStringable(string $json)
    {
        $obj = Document::fromJSON($json)->toPHP();
        $this->assertEquals('2022-04-01T12:34:56+09:00', (string) $obj);
    }
}
