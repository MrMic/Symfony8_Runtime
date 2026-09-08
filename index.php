<?php

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel;

require_once __DIR__ . '/vendor/autoload_runtime.php';

class AppKernel extends Kernel
{
    use MicroKernelTrait;
}

return function () {

    return new AppKernel('dev', true);

};
