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
        var_dump($configs);
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        var_dump($config);

     //   $container->set('mongodb', new \MongoDB\Driver\Manager($config['mongodb']['url']));
    }

}
