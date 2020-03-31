<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

use LogicException;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Minimal Repository implementation
 */
class DefaultRepository implements Repository
{

    protected $manager;
    protected $dbName;
    protected $collectionName;
    protected $logger;

    public function __construct(Manager $manager, string $dbName, string $collectionName, LoggerInterface $log)
    {
        $this->manager = $manager;
        $this->dbName = $dbName;
        $this->collectionName = $collectionName;
        $this->logger = $log;
    }

    protected function getNamespace(): string
    {
        return $this->dbName . '.' . $this->collectionName;
    }

    public function save($documentOrArray): void
    {
        if (!is_array($documentOrArray)) {
            $documentOrArray = [$documentOrArray];
        }

        $bulk = new BulkWrite();

        foreach ($documentOrArray as $doc) {
            if (!($doc instanceof Root)) {
                throw new LogicException("Could only insert objects implementing " . Root::class);
            }

            if ($doc->isNew()) {
                $id = $bulk->insert($doc);
                $doc->setPk($id);
            } else {
                $bulk->update(['_id' => $doc->getPk()], $doc);
            }
        }

        $result = $this->manager->executeBulkWrite($this->getNamespace(), $bulk);
        // @todo some managment of $result
    }

    public function load(string $pk): Root
    {
        $cursor = $this->manager->executeQuery($this->getNamespace(), new Query(['_id' => new ObjectId($pk)], ['limit' => 1]));

        $rows = iterator_to_array($cursor);
        if (count($rows) !== 1) {
            throw new RuntimeException("The document with _id='$pk' was not found.");
        }

        $found = $rows[0];
        if (!($found instanceof Root)) {
            throw new LogicException("The record found for $pk is not an object implementing " . Root::class);
        }

        return $found;
    }

    public function search(array $filter = [], array $excludedField = [], string $descendingSortField = null): \Iterator
    {
        $options = [];

        // options preference on projection
        if (count($excludedField)) {
            $options['projection'] = array_fill_keys($excludedField, 0);
            // in any case we don't exclude these two fields for BSON serialization :
            unset($options['projection']['_id']);
            unset($options['projection']['__pclass']);
        }
        // option for sorting
        if (!empty($descendingSortField)) {
            $options['sort'] = [$descendingSortField => -1];
        }

        $cursor = $this->manager->executeQuery($this->getNamespace(), new Query($filter, $options));

        return new \IteratorIterator($cursor);
    }

    public function searchAutocomplete(string $field, string $startWith, int $limit = 20): array
    {
        $cursor = $this->manager->executeQuery($this->getNamespace(), new Query(
                [$field => new Regex('^' . $startWith, 'i')],
                ['limit' => $limit, 'sort' => [$field => 1], 'projection' => [$field => true]]
        ));

        return $cursor->toArray();
    }

    public function delete($documentOrArray): void
    {
        if (!is_array($documentOrArray)) {
            $documentOrArray = [$documentOrArray];
        }

        $bulk = new BulkWrite();

        foreach ($documentOrArray as $doc) {
            if (!($doc instanceof Root)) {
                throw new LogicException("Could only delete objects implementing " . Root::class);
            }

            if ($doc->isNew()) {
                throw new LogicException("Cannot delete a non-inserted object");
            } else {
                $bulk->delete(['_id' => $doc->getPk()]);
            }
        }

        $result = $this->manager->executeBulkWrite($this->getNamespace(), $bulk);
        // @todo some managment of $result
    }

}
