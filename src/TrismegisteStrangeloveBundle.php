<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove;

use Override;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Trismegiste\Strangelove\DependencyInjection\RepositoryAutoConfig;
use Trismegiste\Strangelove\MongoDb\DefaultRepository;

/**
 * The bundle
 */
class TrismegisteStrangeloveBundle extends AbstractBundle
{
    protected string $extensionAlias = 'strangelove';

    #[\Override]
    public function loadExtension(array $config, ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        // default service for this bundle
        $configurator->import('../config/services.yaml');

        $configurator->services()
                ->get('mongodb')
                ->arg(0, $config['mongodb']['url']);

        $configurator->services()
                ->get('mongodb.factory')
                ->arg('$dbName', $config['mongodb']['dbname']);

        $configurator->parameters()
                ->set('mongodb.dbname', $config['mongodb']['dbname']);

        $container->registerForAutoconfiguration(DefaultRepository::class)
                ->addTag('mongodb.repository');
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RepositoryAutoConfig());
    }

    #[Override]
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
                ->children()
                /**/->arrayNode('mongodb')
                /**/->info("The MongoDb configuration")
                /*    */->children()
                /*        */->scalarNode('url')
                /*            */->defaultValue('mongodb://localhost:27017')
                /*            */->info("Full url to MongoDb server starting with 'mongodb://' (don't forget the port, usually 27017)")
                /*        */->end()
                /*        */->scalarNode('dbname')
                /*            */->isRequired()
                /*            */->cannotBeEmpty()
                /*            */->info("The database name in MongoDb")
                /*        */->end()
                /*    */->end()
                /**/->end()
                ->end()
        ;
    }

    #[Override]
    public function prependExtension(ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('twig', [
            'paths' => [$this->getPath() . '/templates' => 'Strangelove']
        ]);
    }
}
