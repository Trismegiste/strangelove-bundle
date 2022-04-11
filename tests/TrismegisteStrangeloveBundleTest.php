<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Trismegiste\Strangelove\TrismegisteStrangeloveBundle;

class TrismegisteStrangeloveBundleTest extends TestCase
{

    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new TrismegisteStrangeloveBundle();
    }

    public function testContainerExtension()
    {
        $this->assertInstanceOf(ExtensionInterface::class, $this->sut->getContainerExtension());
    }

    public function testCompilerPass()
    {
        $cont = $this->createMock(ContainerBuilder::class);
        $cont->expects($this->exactly(2))
            ->method('addCompilerPass');
        $this->sut->build($cont);
    }

}
