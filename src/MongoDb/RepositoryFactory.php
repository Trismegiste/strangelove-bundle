<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

use MongoDB\Driver\Manager;

/**
 * RepositoryFactory creates Repositry attached to a collection
 */
class RepositoryFactory {

    protected $manager;
    protected $dbName;

    public function __construct(Manager $manager, string $dbName) {
        $this->manager = $manager;
        $this->dbName = $dbName;
    }

    public function create(string $collectionName): Repository {
        return new DefaultRepository($this->manager, $this->dbName, $collectionName);
    }

}
