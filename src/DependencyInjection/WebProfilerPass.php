<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Compiler pass for configuring web profiler
 */
class WebProfilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('twig.loader.native_filesystem')
                ->addMethodCall('addPath', [dirname(dirname(__DIR__)) . '/templates', 'Strangelove']);
    }

}
