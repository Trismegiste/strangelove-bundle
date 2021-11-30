<?php

namespace Tests\Fixtures;

/**
 * Fixtures
 */
class Vector implements \Trismegiste\Toolbox\MongoDb\Root
{

    use \Trismegiste\Toolbox\MongoDb\RootImpl;

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
