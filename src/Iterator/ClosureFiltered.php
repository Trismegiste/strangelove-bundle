<?php

namespace Trismegiste\Toolbox\Iterator;

use Iterator;

class ClosureFiltered {

    protected $iter;
    protected $strategy;

    public function __construct(Iterator $iter, callable $strat) {
        $this->strategy = $strat;
        $this->iter = $iter;
    }

}
