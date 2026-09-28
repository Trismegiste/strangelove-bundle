<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove;

use Override;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Trismegiste\Strangelove\DependencyInjection\RepositoryAutoConfig;
use Trismegiste\Strangelove\DependencyInjection\StrangeloveExtension;
use Trismegiste\Strangelove\DependencyInjection\WebProfilerPass;
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

        $definition = $container->getDefinition('mongodb');
        $definition->replaceArgument(0, $config['mongodb']['url']);

        $definition = $container->getDefinition('mongodb.factory');
        $definition->replaceArgument('$dbName', $config['mongodb']['dbname']);

        $container->setParameter('mongodb.dbname', $config['mongodb']['dbname']);

        $container->registerForAutoconfiguration(DefaultRepository::class)
                ->addTag('mongodb.repository');
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new RepositoryAutoConfig());
        $container->addCompilerPass(new WebProfilerPass());
    }

    #[Override]
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
                ->children()
                /**/->arrayNode('mongodb')
                /*    */->children()
                /*        */->scalarNode('url')
                /*            */->defaultValue('mongodb://localhost:27017')
                /*        */->end()
                /*        */->scalarNode('dbname')
                /*            */->isRequired()
                /*            */->cannotBeEmpty()
                /*        */->end()
                /*    */->end()
                /**/->end()
                ->end()
        ;
    }
}
