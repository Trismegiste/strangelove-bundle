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
class RepositoryFactoryTest extends TestCase
{

    protected $sut;

    protected function setUp(): void
    {
        $mongo = new Manager('mongodb://localhost:27017');
        $this->sut = new RepositoryFactory($mongo, 'trismegiste_toolbox');
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Repository::class, $this->sut->create('repo_test'));
    }

    public function testPooling()
    {
        $repo1 = $this->sut->create('repo_test');
        $repo1p = $this->sut->create('repo_test');
        $repo2 = $this->sut->create('repo_test2');
        $this->assertEquals(spl_object_id($repo1), spl_object_id($repo1p));
        $this->assertNotEquals(spl_object_id($repo1), spl_object_id($repo2));
    }

}
