<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
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
        yield new WebProfilerBundle;
    }

    private function configureContainer(ContainerConfigurator $container): void
    {
        // Controllers must be autowired services, otherwise their typed
        // arguments (LoggerInterface, Twig\Environment) cannot be resolved.
        $container->services()
            ->defaults()
            ->autowire()
            ->autoconfigure()
            ->load('App\\', dirname(__DIR__) . '/src');

        $container->extension('web_profiler', [
            'toolbar' => $this->getEnvironment() === 'dev',
        ]);

        $container->extension('framework', [
            'profiler' => ['collect_serializer_data' => $this->getEnvironment() === 'dev'],
        ]);
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.php')->prefix('_profiler');
        $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.php')->prefix('_wdt');
        $routes->import(__DIR__ . '/*Controller.php', 'attribute');
    }
}
