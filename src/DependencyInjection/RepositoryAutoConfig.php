<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass for injecting mongodb manager and database name into every services tagged as 'mongodb.repository'
 * Are included : every DefaultRepository services and its subclasses
 */
class RepositoryAutoConfig implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
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
            $repoService->addTag('monolog.logger', ['channel' => 'strangelove']); // if monolog is present, we set the channel
        }
    }
}
