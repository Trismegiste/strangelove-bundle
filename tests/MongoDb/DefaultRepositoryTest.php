<?php

/*
 * Toolbox
 */

namespace Tests\Toolbox\MongoDb;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Atom;
use Tests\Fixtures\Hadron;
use Tests\Fixtures\Lepton;
use Tests\Fixtures\Nucleus;
use Tests\Fixtures\Quark;
use Trismegiste\Toolbox\MongoDb\DefaultRepository;

/**
 * Description of DefaultRepositoryTest
 */
class DefaultRepositoryTest extends TestCase {

    protected $mongo;
    protected $sut;

    protected function createAtom(int $z, int $w): Atom {
        $up = new Quark('up', 2 / 3);
        $down = new Quark('down', -1 / 3);
        $proton = new Hadron('proton', [$up, $up, $down]);
        $neutron = new Hadron('neutron', [$up, $down, $down]);
        $nucleus = new Nucleus(array_merge(array_fill(0, $z, $proton), array_fill($z, $w - $z, $neutron)));
        $electron = new Lepton('electron');
        $atom = new Atom($nucleus, array_fill(0, $z, $electron));

        return $atom;
    }

    protected function setUp(): void {
        $this->mongo = new Manager('mongodb://localhost:27017');
        $this->sut = new DefaultRepository($this->mongo, 'trismegiste_toolbox', 'repo_test');
    }

    public function testReset() {
        $bulk = new BulkWrite(['ordered' => true]);
        $bulk->delete([]);
        $result = $this->mongo->executeBulkWrite('trismegiste_toolbox.repo_test', $bulk);
        $this->assertTrue($result->isAcknowledged());
    }

    public function testSave() {
        $doc = $this->createAtom(92, 235);
        $this->sut->save($doc);
        $this->assertRegExp('/^[a-f0-9]{24}$/', $doc->getPk());

        return (string) $doc->getPk();
    }

    /** @depends testSave */
    public function testLoad(string $pk) {
        $doc = $this->sut->load($pk);
        $this->assertInstanceOf(Atom::class, $doc);
        $this->assertEquals(92, $doc->getAtomicNumber());
    }

    public function testSearch() {
        $iter = $this->sut->search();
        $result = iterator_to_array($iter);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Atom::class, $result[0]);

        return (string) $result[0]->getPk();
    }

    /** @depends testSearch */
    public function testUpdate(string $pk) {
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

    public function testProjection() {
        $iter = $this->sut->search([], ['electron']); // we don't care about electrons
        list($atom) = iterator_to_array($iter);
        // the property array 'electron' in Atom was not restored, therefore, it creates an error when you array_push
        $this->expectError();
        $this->expectErrorMessage('null given');
        $atom->addElectron(new Lepton('electron'));
    }

}
