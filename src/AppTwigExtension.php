<?php

namespace App;

use Twig\Attribute\AsTwigFunction;

class AppTwigExtension
{
    #[AsTwigFunction('pluralize')]
    public function pluralize(int $quantity, string $singular, ?string $plural = null): string
    {
        $plural ??= $singular . 's';

        return sprintf('%d %s', $quantity, $quantity === 1 ? $singular : $plural);
    }
}
