<?php

/*
 * Toolbox
 */

class BsonDateTimeTest extends \PHPUnit\Framework\TestCase
{

    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new Trismegiste\Toolbox\MongoDb\Type\BsonDateTime('2022-04-01 12:34:56', new DateTimeZone('Asia/Tokyo'));
    }

    public function testSerialize()
    {
        $dump = \MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($this->sut));
        $this->assertJson($dump);

        return $dump;
    }

    /** @depends testSerialize */
    public function testUnserialize(string $json)
    {
        $obj = \MongoDB\BSON\toPHP(MongoDB\BSON\fromJSON($json));
        $this->assertEquals('2022-04-01T12:34:56+09:00', $obj->format(DateTime::ATOM));
    }

    /** @depends testSerialize */
    public function testStringable(string $json)
    {
        $obj = \MongoDB\BSON\toPHP(MongoDB\BSON\fromJSON($json));
        $this->assertEquals('2022-04-01T12:34:56+09:00', (string) $obj);
    }

}
