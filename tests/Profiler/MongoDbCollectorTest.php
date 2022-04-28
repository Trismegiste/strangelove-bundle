<?php

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Trismegiste\Strangelove\Profiler\CollectingSubscriber;
use Trismegiste\Strangelove\Profiler\MongoDbCollector;

class MongoDbCollectorTest extends TestCase
{

    protected $subscriber;
    protected $sut;

    protected function setUp(): void
    {
        $this->subscriber = $this->createMock(CollectingSubscriber::class);
        $this->sut = new MongoDbCollector($this->subscriber);
    }

    public function testCollecting()
    {
        $mongo = new Manager('mongodb://localhost:27017');
        $bulk = new BulkWrite();
        $bulk->delete([]);

        $this->subscriber->expects($this->once())->method('commandStarted');
        $this->subscriber->expects($this->once())->method('commandSucceeded');

        $mongo->executeBulkWrite('trismegiste_toolbox.repo_test', $bulk);
    }

    public function testName()
    {
        $this->assertEquals('strangelove', $this->sut->getName());
    }

}
