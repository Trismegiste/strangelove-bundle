<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\Composer;

use Composer\Script\Event;
use Symfony\Flex\Configurator;
use Symfony\Flex\Lock;
use Symfony\Flex\Options;

/**
 * Hu ?
 */
class Hook
{

    public static function postInstallCmd(Event $event)
    {
        echo "TOTO\n";
        $configurator = new Configurator($event->getComposer(), $event->getIO(), new Options());

        $recipe = new SelfRecipe();
        $configurator->install($recipe, new Lock('recipe.lock'));
    }

}
