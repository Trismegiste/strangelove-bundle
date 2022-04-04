<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\MongoDb;

use MongoDB\Driver\Manager;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * RepositoryFactory creates Repositry attached to a collection
 * @todo needs an Interface for a real Factory Method DP
 */
class RepositoryFactory
{

    protected $manager;
    protected $dbName;
    protected $collection;
    protected $logger;

    public function __construct(Manager $manager, string $dbName, LoggerInterface $log = null)
    {
        $this->manager = $manager;
        $this->dbName = $dbName;
        $this->collection = [];
        $this->logger = is_null($log) ? new NullLogger() : $log;
    }

    public function create(string $collectionName): Repository
    {
        if (!array_key_exists($collectionName, $this->collection)) {
            $this->collection[$collectionName] = new DefaultRepository($this->manager, $this->dbName, $collectionName, $this->logger);
        }

        return $this->collection[$collectionName];
    }

}
