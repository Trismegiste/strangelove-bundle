<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\Iterator;

use Iterator;

/**
 * This is a Decorator for an Iterator
 * You provide a callable for decorating current()
 */
class ClosureDecorator implements Iterator
{

    protected $iter;
    protected $strategy;

    /**
     * Ctor
     * @param Iterator $iter any Iterator
     * @param callable $strat a callable that will be called with one argument : Iterator::current()
     */
    public function __construct(Iterator $iter, callable $strat)
    {
        $this->strategy = $strat;
        $this->iter = $iter;
    }

    /**
     * Returns the current element of the iterator decorated by the callable
     * @return mixed
     */
    public function current()
    {
        return ($this->strategy)($this->iter->current());
    }

    // next methods are just redirections
    public function key()
    {
        return $this->iter->key();
    }

    public function next(): void
    {
        $this->iter->next();
    }

    public function rewind(): void
    {
        $this->iter->rewind();
    }

    public function valid(): bool
    {
        return $this->iter->valid();
    }

}
