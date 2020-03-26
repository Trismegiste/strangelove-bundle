<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\MongoDb;

/**
 * Minimal repository for MongoDb
 * 
 * Convention : 
 *  * Method "searchXXXX" could find zero, one or many record
 *  * Method "findXXXX" could find at least one record, throw exception if otherwise
 *  * Method "findOneXXXX" must find one item or throw exception otherwise
 */
interface Repository {

    public function search(array $filter = [], array $excludedField = [], string $descendingSortField = null): \Iterator;

    public function load(string $pk): Root;

    public function save($documentOrArray): void;

    public function delete($documentOrArray): void;

    public function searchAutocomplete(string $field, string $startWith, $limit = 20);
}
