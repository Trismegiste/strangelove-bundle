<?php

namespace Tests\Fixtures;

/**
 * Fixtures
 */
class Vector implements \Trismegiste\Strangelove\MongoDb\Root
{

    use \Trismegiste\Strangelove\MongoDb\RootImpl;

    protected $data;

    public function setContent(array $cnt)
    {
        $this->data = $cnt;
    }

    public function getContent(): array
    {
        return $this->data;
    }

}
