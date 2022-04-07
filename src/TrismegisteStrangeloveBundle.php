<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * The bundle
 */
class TrismegisteStrangeloveBundle extends Bundle
{

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new DependencyInjection\StrangeloveExtension();
    }

}
