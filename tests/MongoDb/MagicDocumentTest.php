<?php

use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Tests\Toolbox\MongoDb\MongoCheck;
use Trismegiste\Toolbox\MongoDb\MagicDocument;

class MagicDocumentTest extends TestCase
{

    use MongoCheck;

    protected $mongo;
    protected $sut;

    protected function setUp(): void
    {
        $this->mongo = new Manager('mongodb://localhost:27017');
        $this->sut = new MagicDocument();
        $this->ping($this->mongo, 'trismegiste_toolbox');
    }

    protected function tearDown(): void
    {
        unset($this->sut);
    }

    public function testPrimaryKey()
    {
        $this->assertTrue($this->sut->isNew());
        $this->sut->setPk(new ObjectId());
        $this->assertFalse($this->sut->isNew());
        $this->assertInstanceOf(ObjectId::class, $this->sut->getPk());
    }

    public function testCannotChangePrimaryKey()
    {
        $this->expectException(LogicException::class);
        $this->sut->setPk(new ObjectId());
        $this->sut->setPk(new ObjectId());
    }

    public function testProperties()
    {
        $this->sut['answer'] = 42;
        $this->assertEquals(42, $this->sut['answer']);
    }

}
