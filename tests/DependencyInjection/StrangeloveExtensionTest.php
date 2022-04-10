<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Trismegiste\Strangelove\DependencyInjection\StrangeloveExtension;
use Trismegiste\Strangelove\MongoDb\DefaultRepository;

class StrangeloveExtensionTest extends TestCase
{

    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new StrangeloveExtension();
    }

    public function testInjection()
    {
        $cont = new ContainerBuilder();
        $cont->setDefinition('mongodb', new Definition());
        $cont->setParameter('mongodb.dbname', 'yolo');

        $def = new Definition(DefaultRepository::class, ['$manager' => 'dummy', '$dbName' => 'dummy']);
        $def->addTag('mongodb.repository');
        $cont->setDefinition('myrepo', $def);

        $this->sut->load(['strangelove' => [
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

}
