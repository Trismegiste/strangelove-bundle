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

    public function testPass()
    {
        $cont = new ContainerBuilder();
        $cont->setParameter('mongodb.dbname', 'yolo');
        $cont->setDefinition('mongodb', new Definition(DefaultRepository::class));

        $this->sut->process($cont);
    }

}
