<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

use MongoDB\Driver\Manager;

/**
 * RepositoryFactory creates Repositry attached to a collection
 * @todo turn this into an Object Pool pattern.
 * @todo needs an Interface for a real Factory Method DP
 */
class RepositoryFactory
{

    protected $manager;
    protected $dbName;
    protected $collection;

    public function __construct(Manager $manager, string $dbName)
    {
        $this->manager = $manager;
        $this->dbName = $dbName;
        $this->collection = [];
    }

    public function create(string $collectionName): Repository
    {
        if (!array_key_exists($collectionName, $this->collection)) {
            $this->collection[$collectionName] = new DefaultRepository($this->manager, $this->dbName, $collectionName);
        }

        return $this->collection[$collectionName];
    }

}
