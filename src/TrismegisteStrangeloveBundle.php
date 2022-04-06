<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * The bundle
 */
class TrismegisteStrangeloveBundle extends Bundle
{

    public function getContainerExtension()
    {
        return new DependencyInjection\StrangeloveExtension();
    }

}
