<?php

/*
 * Strangelove
 */

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Trismegiste\Strangelove\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{

    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new Configuration();
    }

    public function testBuilder()
    {
        $cfg = $this->sut->getConfigTreeBuilder();
        $this->assertInstanceOf(TreeBuilder::class, $cfg);
    }

}
