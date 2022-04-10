<?php

/*
 * Strangelove
 */

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Trismegiste\Strangelove\DependencyInjection\RepositoryAutoConfig;
use Trismegiste\Strangelove\MongoDb\DefaultRepository;

class RepositoryAutoConfigTest extends TestCase
{

    protected $sut;

    protected function setUp(): void
    {
        $this->sut = new RepositoryAutoConfig();
    }

    public function testNoMongoService()
    {
        $cont = new ContainerBuilder();
        $this->sut->process($cont);
        $this->assertFalse($cont->hasDefinition('mongodb'));
    }

    public function testValidConfig()
    {
        $cont = new ContainerBuilder();
        $cont->setParameter('mongodb.dbname', 'yolo');
        $cont->setDefinition('mongodb', new Definition());

        $def = new Definition(DefaultRepository::class, ['$manager' => 'dummy', '$dbName' => 'dummy']);
        $def->addTag('mongodb.repository');
        $cont->setDefinition('myrepo', $def);

        $this->sut->process($cont);
        $this->assertTrue($cont->hasDefinition('myrepo'));
    }

}
