<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\Composer;

/**
 * Description of SelfRecipe
 *
 * @author flo
 */
class SelfRecipe extends \Symfony\Flex\Recipe
{

    public function __construct()
    {
        
    }

    public function getManifest(): array
    {
        return [
            'bundles' => [
                'Yolo' => ['all']
            ]
        ];
    }

}
