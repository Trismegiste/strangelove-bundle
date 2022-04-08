<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Trismegiste\Strangelove\DependencyInjection\RepositoryAutoConfig;
use Trismegiste\Strangelove\DependencyInjection\StrangeloveExtension;

/**
 * The bundle
 */
class TrismegisteStrangeloveBundle extends Bundle
{

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new StrangeloveExtension();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RepositoryAutoConfig());
    }

}
