<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle;
        yield new TwigBundle;
    }

    private function configureContainer(ContainerConfigurator $container): void
    {
        // Controllers must be autowired services, otherwise their typed
        // arguments (LoggerInterface, Twig\Environment) cannot be resolved.
        $container->services()
            ->defaults()->autowire()->autoconfigure()
            ->load('App\\', dirname(__DIR__) . '/src');
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(__DIR__ . '/*Controller.php', 'attribute');
    }
}
