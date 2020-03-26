<?php

/*
 * Toolbox
 */

namespace Tests\Toolbox\MongoDb;

use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Trismegiste\Toolbox\MongoDb\RepositoryFactory;

/**
 * Description of RepositoryFactoryTest
 */
class RepositoryFactoryTest extends TestCase {

    public function testCreate() {
        $mongo = $this->createStub(Manager::class);
        $sut = new RepositoryFactory($mongo, 'database');

        $this->assertInstanceOf(\Trismegiste\Toolbox\MongoDb\Repository::class, $sut->create('collection'));
    }

}
