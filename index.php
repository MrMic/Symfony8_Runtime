<?php

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

require_once __DIR__ . '/vendor/autoload_runtime.php';

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle;
        yield new TwigBundle;
    }

    #[Route(path: '/', name: 'home')]
    public function home(
        Request $request,
        LoggerInterface $logger,
        Environment $twig,
    ): Response {
        /* dd($request->query->all()); */
        /* $logger->info('Homepage accessed', ['ip' => $request->getClientIp()]); */
        $name = $request->query->get('name', 'world');
        $currentTime = new DateTimeImmutable(timezone: new DateTimeZone('Europe/Paris'))->format('H:i:s');

        return new Response(
            $twig->render('home.html.twig', [
                'name' => $name,
                'current_time' => $currentTime,
            ])
        );
    }

    #[Route(path: '/about', name: 'about')]
    public function about(Environment $twig): Response
    {
        return new Response($twig->render('about.html.twig'));
    }
}

return function (array $context) {
    /* dd($context); */

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);

};
