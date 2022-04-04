<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Strangelove\Iterator\ClosureDecorator;

class ClosureDecoratorTest extends TestCase {

    public function testIterate() {
        $data = [111, 222, 333];
        $sut = new ClosureDecorator(new ArrayIterator($data), function($value) {
            return "n=$value";
        });

        foreach ($sut as $idx => $value) {
            $p = $idx + 1;
            $this->assertEquals("n=$p$p$p", $value);
        }
    }

}
