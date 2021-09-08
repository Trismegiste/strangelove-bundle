<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Fixtures;

/**
 * Description of Vector
 *
 * @author flo
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
