<?php

/*
 * Strangelove bundle
 */

namespace Trismegiste\Strangelove\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Trismegiste\Strangelove\MongoDb\DefaultRepository;

/**
 * Config extension
 */
class StrangeloveExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('mongodb');
        $definition->replaceArgument(0, $config['mongodb']['url']);

        $definition = $container->getDefinition('mongodb.factory');
        $definition->replaceArgument('$dbName', $config['mongodb']['dbname']);

        $container->setParameter('mongodb.dbname', $config['mongodb']['dbname']);

        $container->registerForAutoconfiguration(DefaultRepository::class)
            ->addTag('mongodb.repository')
        ;
    }

}
