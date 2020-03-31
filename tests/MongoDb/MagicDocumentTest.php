<?php

use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Tests\Toolbox\MongoDb\MongoCheck;
use Trismegiste\Toolbox\MongoDb\DefaultRepository;
use Trismegiste\Toolbox\MongoDb\MagicDocument;

class MagicDocumentTest extends TestCase
{

    use MongoCheck;

    protected $sut;
    protected $repository;

    protected function setUp(): void
    {
        $mongo = new Manager('mongodb://localhost:27017');
        $this->ping($mongo, 'trismegiste_toolbox');
        $this->repository = new DefaultRepository($mongo, 'trismegiste_toolbox', 'magic', new NullLogger());
        $this->sut = new MagicDocument();
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
        $this->assertArrayHasKey('answer', $this->sut);
        $this->repository->save($this->sut);

        return (string) $this->sut->getPk();
    }

    /** @depends testProperties */
    public function testLoad(string $pk)
    {
        $doc = $this->repository->load($pk);
        $this->assertEquals(42, $doc['answer']);
        $doc['answer'] = 51;
        $this->assertEquals(51, $doc['answer']);
    }

    /** @depends testProperties */
    public function testFailOnAddingNewProperty(string $pk)
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('does not exist');
        $doc = $this->repository->load($pk);
        $doc['question'] = 51;
    }

    public function invalidPropertyName()
    {
        return [
            [null],
            [''],
            [123],
            [[123]]
        ];
    }

    /** @dataProvider invalidPropertyName */
    public function testNullKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only a string');
        $this->sut[$key] = 666;
    }

    public function testNullDefaultOnNew()
    {
        $this->assertNull($this->sut['question']);
    }

    /** @depends testProperties */
    public function testFailNonExistingProperty(string $pk)
    {
        $this->expectError();
        $this->expectErrorMessage('Undefined');
        $doc = $this->repository->load($pk);
        $this->assertNull($doc['question']);
    }

    /** @depends testProperties */
    public function testFailUnsetPk(string $pk)
    {
        $doc = $this->repository->load($pk);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('primary key');
        unset($doc['_id']);
    }

    public function testUnset()
    {
        $this->sut['answer'] = 42;
        $this->assertEquals(42, $this->sut['answer']);
        $this->assertArrayHasKey('answer', $this->sut);
        unset($this->sut['answer']);
        $this->assertArrayNotHasKey('answer', $this->sut);
    }

    public function testPrimaryKeyArraySetter()
    {
        $obj = new ObjectId();
        $this->sut['_id'] = $obj;
        $this->assertEquals($obj, $this->sut->getPk());
    }

    /** @depends testProperties */
    public function testUpdate(string $pk)
    {
        $doc = $this->repository->load($pk);
        $this->assertEquals(42, $doc['answer']);
        $doc['answer'] = 51;
        $this->assertEquals(51, $doc['answer']);
        $this->repository->save($doc);

        $doc2 = $this->repository->load($pk);
        $this->assertEquals(51, $doc2['answer']);
    }

}
