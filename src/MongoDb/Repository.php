<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\MongoDb;

/**
 * Minimal repository interface for MongoDb
 */
interface Repository
{

    /**
     * Returns an Iterator of Root objects for the query
     * @param array $filter the query
     * @param array $excludedField fields to exclude if you don't need them
     * @param string $descendingSortField field name for sorting in descending order
     * @return \Iterator Warning : this iterator is not rewindable (since it's a wrapper for a Cursor
     */
    public function search(array $filter = [], array $excludedField = [], string $descendingSortField = null): \Iterator;

    /**
     * Returns the first document found in the collection or null
     * @param array $filter the query
     * @param array $excludedField fields to exclude if you don't need them
     * @return \MongoDB\BSON\Persistable|null
     */
    public function searchOne(array $filter = [], array $excludedField = []): ?\MongoDB\BSON\Persistable;

    /**
     * Load ONE object stored in the collection by its PK
     * @param string $pk the primary key (a.k.a field "_id")
     * @return \Trismegiste\Strangelove\MongoDb\Root
     */
    public function load(string $pk): Root;

    /**
     * Persists one or many document in the collection
     * @param Root|Root[] $documentOrArray a Root object or an array of Root objects to persist
     */
    public function save($documentOrArray): void;

    /**
     * Deletes one or many document in the collection
     * @param Root|Root[] $documentOrArray a Root object or an array of Root objects to delete
     */
    public function delete($documentOrArray): void;

    /**
     * Gets an array of possible choices for one field starting with the provided string.
     * NOTE : this method DOES NOT return full objects but only a minimal stdClass 
     * with the content of the provided field and the _id
     * 
     * @param string $field the field name to search into
     * @param string $startWith the search string start with...
     * @param int $limit a limit for result : since we return an array, please don't load the memory
     */
    public function searchAutocomplete(string $field, string $startWith, int $limit = 20);

    /**
     * Increments a field in a entity (direct access without loading entity from mongodb
     * @param string $pk if entity primary key
     * @param string $fieldName the field name to increment
     * @param int $amount how much to increment
     */
    public function incField(string $pk, string $fieldName, int $amount = 1): void;
}
