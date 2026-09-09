<?php

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

require_once __DIR__ . '/vendor/autoload_runtime.php';

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}

return function (array $context) {
    /* dd($context); */

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);

};
