<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass for autoconfiguring DefaultRepository subclasses
 */
class RepositoryAutoConfig implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->has('mongodb')) {
            return;
        }

        $dbName = $container->getParameter('mongodb.dbname');

        // find all service IDs with the mongodb.repository tag
        $taggedServices = $container->findTaggedServiceIds('mongodb.repository');
        foreach ($taggedServices as $id => $tags) {
            $repoService = $container->getDefinition($id);
            $repoService->replaceArgument('$manager', new Reference('mongodb'));
            $repoService->replaceArgument('$dbName', $dbName);
        }

        $container->getDefinition('twig.loader.native_filesystem')
            ->addMethodCall('addPath', [__DIR__ . '/../../templates', 'Strangelove']);
    }

}
