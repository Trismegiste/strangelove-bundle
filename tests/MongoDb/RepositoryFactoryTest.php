<?php

/*
 * Toolbox
 */

namespace Tests\Toolbox\MongoDb;

use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Trismegiste\Toolbox\MongoDb\Repository;
use Trismegiste\Toolbox\MongoDb\RepositoryFactory;

/**
 * Description of RepositoryFactoryTest
 */
class RepositoryFactoryTest extends TestCase {

    public function testCreate() {
        $mongo = new Manager('mongodb://localhost:27017');
        $sut = new RepositoryFactory($mongo, 'trismegiste_toolbox');

        $this->assertInstanceOf(Repository::class, $sut->create('repo_test'));
    }

}
