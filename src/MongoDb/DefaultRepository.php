<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\MongoDb;

use MongoDB\Driver\Manager;
use Override;
use Psr\Log\LoggerInterface;

/**
 * Minimal Repository implementation with auto-injection of config
 */
class DefaultRepository extends AbstractRepository
{

    protected string $dbName;
    protected string $collectionName;

    public function __construct(Manager $manager, string $dbName, string $collectionName, LoggerInterface $logger)
    {
        parent::__construct($manager, $logger);
        $this->dbName = $dbName;
        $this->collectionName = $collectionName;
    }

    #[Override]
    protected function getCollectionName(): string
    {
        return $this->collectionName;
    }

    #[Override]
    protected function getDbName(): string
    {
        return $this->dbName;
    }
}
