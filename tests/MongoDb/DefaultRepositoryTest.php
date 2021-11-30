<?php

/*
 * Toolbox
 */

namespace Tests\Toolbox\MongoDb;

use LogicException;
use MongoDB\BSON\ObjectIdInterface;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;
use stdClass;
use Tests\Fixtures\Atom;
use Tests\Fixtures\AtomBuilder;
use Tests\Fixtures\Lepton;
use Trismegiste\Toolbox\MongoDb\DefaultRepository;

/**
 * DefaultRepositoryTest tests DefaultRepository
 */
class DefaultRepositoryTest extends TestCase
{

    use AtomBuilder,
        MongoCheck;

    protected $mongo;
    protected $sut;
    protected $logger;

    protected function setUp(): void
    {
        $this->mongo = new Manager('mongodb://localhost:27017');
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->sut = new DefaultRepository($this->mongo, 'trismegiste_toolbox', 'repo_test', $this->logger);
        $this->ping($this->mongo, 'trismegiste_toolbox');
    }

    public function testReset()
    {
        $bulk = new BulkWrite();
        $bulk->delete([]);
        $result = $this->mongo->executeBulkWrite('trismegiste_toolbox.repo_test', $bulk);
        $this->assertTrue($result->isAcknowledged());
    }

    public function testSave()
    {
        $doc = $this->createAtom('U235', 92, 235);
        $this->sut->save($doc);
        $this->assertRegExp('/^[a-f0-9]{24}$/', $doc->getPk());

        return (string) $doc->getPk();
    }

    /** @depends testSave */
    public function testLoad(string $pk)
    {
        $doc = $this->sut->load($pk);
        $this->assertInstanceOf(Atom::class, $doc);
        $this->assertEquals(92, $doc->getAtomicNumber());
    }

    public function testSearch()
    {
        $iter = $this->sut->search();
        $result = iterator_to_array($iter);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Atom::class, $result[0]);

        return (string) $result[0]->getPk();
    }

    /** @depends testSearch */
    public function testUpdate(string $pk)
    {
        $doc = $this->sut->load($pk);
        $this->assertFalse($doc->isIonized());
        $doc->looseElectron();
        $this->assertTrue($doc->isIonized());

        $this->sut->save($doc);
        $reload = $this->sut->load($pk);
        $this->assertEquals($doc, $reload);
        unset($doc); // just to be sure
        $this->assertTrue($reload->isIonized());
    }

    public function testProjection()
    {
        $iter = $this->sut->search([], ['electron']); // we don't care about electrons
        list($atom) = iterator_to_array($iter);
        // the property array 'electron' in Atom was not restored, therefore, it creates an error when you array_push()
        $this->expectError();
        $this->expectErrorMessage('null given');
        $atom->addElectron(new Lepton('electron'));
    }

    public function dont_testPerformance()
    {
        $pk = [];
        $stopwatch = microtime(true);
        for ($k = 0; $k < 1000; $k++) {
            $doc = $this->createAtom('U235', 92, 235); // 92 + 235*4 ~ 1000 objects per Atom object
            $this->sut->save($doc);
            $pk[] = $doc->getPk();
        }
        $delta = microtime(true) - $stopwatch;
        var_dump($delta);  // about 2.5 seconds for 1 million simple objects on my cheap laptop on Ubuntu
        sleep(1);
        $stopwatch = microtime(true);
        foreach ($pk as $id) {
            $this->sut->load($id);
        }
        $delta = microtime(true) - $stopwatch;
        var_dump($delta);  // about 1.8 seconds for 1 million simple objects on my cheap laptop on Ubuntu
    }

    public function testAutocompleteSearch()
    {
        $result = $this->sut->searchAutocomplete('name', 'U');
        $this->assertCount(1, $result);
        $this->assertInstanceOf(ObjectIdInterface::class, $result[0]->_id);
        $this->assertEquals('U235', $result[0]->name);
    }

    public function testDelete()
    {
        $result = $this->sut->searchAutocomplete('name', 'U');
        $pk = $result[0]->_id;
        $atom = $this->sut->load($pk);
        $this->sut->delete($atom);
        $result = $this->sut->searchAutocomplete('name', 'U');
        $this->assertCount(0, $result);
    }

    public function testDeleteInvalidObject()
    {
        $this->expectException(LogicException::class);
        $this->sut->delete(new stdClass());
    }

    public function testSaveInvalidObject()
    {
        $this->expectException(LogicException::class);
        $this->sut->save(new stdClass());
    }

    public function testDeleteNonInserted()
    {
        $this->expectException(LogicException::class);
        $this->sut->delete($this->createAtom('H', 1, 1));
    }

    public function testNotFound()
    {
        $this->expectException(RuntimeException::class);
        $this->sut->load('123456789012345678901234');
    }

    public function testEmptyArrays()
    {
        $this->sut->save([]);
        $this->sut->delete([]);
    }

    public function testBadDataInCollection()
    {
        $bulk = new BulkWrite();
        $pk = $bulk->insert(['answer' => 42]);
        $this->mongo->executeBulkWrite('trismegiste_toolbox.repo_test', $bulk);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('not an object implementing');
        $this->logger->expects($this->once())
                ->method('alert');

        $this->sut->load((string) $pk);
    }

    public function testFieldIncrementing()
    {
        $obj = new \Tests\Fixtures\Product();
        $obj->counter = 665;
        $this->sut->save($obj);
        $pk = (string) $obj->getPk();
        unset($obj);

        $this->sut->incField($pk, 'counter');
        $newObj = $this->sut->load($pk);
        $this->assertEquals(666, $newObj->counter);
    }

    public function testFieldIncrementingDeep()
    {
        $obj = new \Tests\Fixtures\Vector();
        $obj->setContent(['counter' => 665]);
        $this->sut->save($obj);
        $pk = (string) $obj->getPk();
        unset($obj);

        $this->sut->incField($pk, 'data.counter');
        $newObj = $this->sut->load($pk);
        $this->assertEquals(666, $newObj->getContent()['counter']);
    }

}
