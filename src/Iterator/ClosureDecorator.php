<?php

/*
 * Toolbox
 */

namespace Trismegiste\Toolbox\Iterator;

use Iterator;

/**
 * Description of ClosureDecoratorTest
 */
class ClosureDecorator implements Iterator {

    protected $iter;
    protected $strategy;

    public function __construct(Iterator $iter, callable $strat) {
        $this->strategy = $strat;
        $this->iter = $iter;
    }

    public function current() {
        return ($this->strategy)($this->iter->current());
    }

    public function key() {
        return $this->iter->key();
    }

    public function next(): void {
        $this->iter->next();
    }

    public function rewind(): void {
        $this->iter->rewind();
    }

    public function valid(): bool {
        return $this->iter->valid();
    }

}
