<?php

use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Trismegiste\Strangelove\MongoDb\DefaultRepository;
use Trismegiste\Strangelove\TrismegisteStrangeloveBundle;

class TrismegisteStrangeloveBundleTest extends TestCase
{
    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new TrismegisteStrangeloveBundle();
    }

    public function testCompilerPass()
    {
        $cont = $this->createMock(ContainerBuilder::class);
        $cont->expects($this->atLeastOnce())
                ->method('addCompilerPass');
        $this->sut->build($cont);
    }

    function testExtension()
    {
        $ext = $this->sut->getContainerExtension();

        $cont = new ContainerBuilder();
        $cont->setDefinition('mongodb', new Definition(Manager::class));
        $cont->setParameter('mongodb.dbname', 'yolo');

        $def = new Definition(DefaultRepository::class, ['$manager' => 'dummy', '$dbName' => 'dummy']);
        $def->addTag('mongodb.repository');
        $cont->setDefinition('myrepo', $def);

        $ext->load(['strangelove' => [
                'mongodb' => [
                    'url' => 'mongodb://localhost:27017',
                    'dbname' => 'yolo'
                ]
            ]
                ], $cont);

        $this->assertTrue($cont->hasParameter('mongodb.dbname'));
        $this->assertTrue($cont->hasDefinition('mongodb'));
        $this->assertTrue($cont->hasDefinition('mongodb.factory'));
    }

    function testConfiguration()
    {
        $tb = new TreeBuilder('strangelove');
        $definition = $this->createMock(DefinitionConfigurator::class);
        $definition->expects($this->atLeastOnce())
                ->method('rootNode')
                ->willReturn($tb->getRootNode());

        $this->sut->configure($definition);
        $this->assertEquals("strangelove", $tb->buildTree()->getPath());
    }
}
