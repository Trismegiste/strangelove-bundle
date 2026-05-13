<?php

/*
 * strangelove
 */

namespace Trismegiste\Strangelove\Http;

use MongoDB\BSON\Document;
use MongoDB\BSON\Persistable;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * JsonResponse-like for a Persistable
 */
class BsonResponse extends JsonResponse
{
    public function __construct(Persistable $entity, int $status = 200, array $headers = [])
    {
        parent::__construct(Document::fromPHP($entity)->toRelaxedExtendedJSON(), $status, $headers, true);
    }
}
