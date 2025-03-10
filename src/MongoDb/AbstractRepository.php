<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\MongoDb;

use Iterator;
use IteratorIterator;
use LogicException;
use MongoDB\BSON\Binary;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Command;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\WriteResult;
use Override;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Minimal Repository implementation
 */
abstract class AbstractRepository implements Repository
{

    public function __construct(protected Manager $manager, protected LoggerInterface $logger)
    {
        
    }

    protected function getNamespace(): string
    {
        return $this->getDbName() . '.' . $this->getCollectionName();
    }

    abstract protected function getDbName(): string;

    abstract protected function getCollectionName(): string;

    protected function logResult(WriteResult $result)
    {
        $this->logger->info(sprintf('Write Bulk Result for %s : %d inserted / %d upserted / %d modified / %d deleted',
                        $this->getNamespace(),
                        $result->getInsertedCount(),
                        $result->getUpsertedCount(),
                        $result->getModifiedCount(),
                        $result->getDeletedCount())
        );
        if (count($result->getWriteErrors())) {
            foreach ($result->getWriteErrors() as $error) {
                $this->logger->error($error->getMessage());
            }
        }
    }

    public function save($documentOrArray): void
    {
        if (!is_array($documentOrArray)) {
            $documentOrArray = [$documentOrArray];
        }

        if (count($documentOrArray) === 0) {
            return;
        }

        $bulk = new BulkWrite();
        $this->logger->debug(sprintf("Saving %d document(s) in %s...", count($documentOrArray), $this->getNamespace()));

        foreach ($documentOrArray as $doc) {
            if (!($doc instanceof Root)) {
                throw new LogicException("Could only insert/update objects implementing " . Root::class);
            }

            if ($doc->isNew()) {
                $id = $bulk->insert($doc);
                $doc->setPk($id);
            } else {
                $bulk->update(['_id' => $doc->getPk()], $doc);
            }
        }

        $result = $this->manager->executeBulkWrite($this->getNamespace(), $bulk);
        $this->logResult($result);
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
            $this->logger->alert("There is something wrong in {$this->getCollectionName()} collection since document with pk '$pk' is not implementing " . Root::class);
            throw new LogicException("The record found for $pk is not an object implementing " . Root::class);
        }

        return $found;
    }

    public function search(array $filter = [], array $excludedField = [], string $descendingSortField = null): Iterator
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

        $this->logger->debug('Searching in ' . $this->getCollectionName() . ' for ' . json_encode($filter));
        $cursor = $this->manager->executeQuery($this->getNamespace(), new Query($filter, $options));

        return new IteratorIterator($cursor);
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

        if (count($documentOrArray) === 0) {
            return;
        }

        $bulk = new BulkWrite();
        $this->logger->debug(sprintf("Deleting %d document(s)...", count($documentOrArray)));

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
        $this->logResult($result);
    }

    public function incField(string $pk, string $fieldName, int $amount = 1): void
    {
        $bulk = new BulkWrite();
        $this->logger->debug("Incrementing $fieldName field in document $pk");
        $bulk->update(['_id' => new ObjectId($pk)], ['$inc' => [$fieldName => $amount]]);
        $result = $this->manager->executeBulkWrite($this->getNamespace(), $bulk);
        $this->logResult($result);
    }

    public function searchOne(array $filter = [], array $excludedField = []): ?Root
    {
        $options = ['limit' => 1];

        // options preference on projection
        if (count($excludedField)) {
            $options['projection'] = array_fill_keys($excludedField, 0);
            // in any case we don't exclude these two fields for BSON serialization :
            unset($options['projection']['_id']);
            unset($options['projection']['__pclass']);
        }

        $this->logger->debug('Searching One Document in ' . $this->getCollectionName() . ' for ' . json_encode($filter));
        $cursor = $this->manager->executeQuery($this->getNamespace(), new Query($filter, $options));
        $rows = iterator_to_array($cursor);

        return (count($rows)) ? $rows[0] : null;
    }

    public function searchFieldExistsForClass(string $fqcn, string $fieldName): Cursor
    {
        $query = new Query(['__pclass' => new Binary($fqcn, Binary::TYPE_USER_DEFINED), $fieldName => ['$exists' => true]]);
        $cursor = $this->manager->executeQuery($this->getNamespace(), $query);

        return $cursor;
    }

    public function removeField(string $pk, string $fieldName): void
    {
        $bulk = new BulkWrite();
        $this->logger->debug("Removing $fieldName field in document $pk");
        $bulk->update(['_id' => new ObjectId($pk)], ['$unset' => [$fieldName => '']]);
        $result = $this->manager->executeBulkWrite($this->getNamespace(), $bulk);
        $this->logResult($result);
    }

    #[Override]
    public function readPipeline(array $pipeline): Cursor
    {
        return $this->manager->executeReadCommand($this->getDbName(), new Command([
                            'aggregate' => $this->getCollectionName(),
                            'cursor' => ['batchSize' => 0],
                            'pipeline' => $pipeline
        ]));
    }

    #[Override]
    public function executeCommand(array $commandParameters): mixed
    {
        $cmd = new Command($commandParameters);

        $cursor = $this->manager->executeCommand($this->getDbName(), $cmd);
        $response = $cursor->toArray()[0];

        if (!$response->ok) {
            throw new RuntimeException($response->note);
        }

        return $response;
    }
}
