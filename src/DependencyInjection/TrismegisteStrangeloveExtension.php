<?php

/*
 * Strangelove bundle
 */

namespace Trismegiste\Strangelove\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Config extension
 */
class TrismegisteStrangeloveExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        var_dump($config);
    }

    public function getAlias()
    {
        return 'strangelove';
    }

}