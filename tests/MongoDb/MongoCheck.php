<?php

namespace Tests\Strangelove\MongoDb;

use MongoDB\Driver\Command;
use MongoDB\Driver\Exception\ConnectionTimeoutException;
use MongoDB\Driver\Manager;

trait MongoCheck
{

    public function ping(Manager $cnx, string $db)
    {
        $command = new Command(['ping' => 1]);
        try {
            $cursor = $cnx->executeCommand($db, $command);
            $response = $cursor->toArray()[0];
            $this->assertEquals(1, $response->ok, "MongoDB server is not ready");
        } catch (ConnectionTimeoutException $e) {
            $this->markTestSkipped("MongoDB is not responding : did you forget to launch '$ sudo service mongod start' ?");
        }
    }

}
