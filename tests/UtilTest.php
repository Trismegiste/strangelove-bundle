<?php

class util_test extends \PHPUnit\Framework\TestCase {

    public function test_join_paths() {
        $this->assertEquals('a', join_paths('a'));
        $this->assertEquals('a/b', join_paths('a', 'b'));
        $this->assertEquals('a/b', join_paths('a/', 'b'));
        $this->assertEquals('a/b', join_paths('a', '/b'));
        $this->assertEquals('a/b', join_paths('a/', '/b'));
        $this->assertEquals('/a/b', join_paths('/a', '/b'));
        $this->assertEquals('/a/b', join_paths('/a/', '/b'));
        $this->assertEquals('./b', join_paths('.', 'b'));
    }

}
