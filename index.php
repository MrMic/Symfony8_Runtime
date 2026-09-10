<?php

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Attribute\Route;

require_once __DIR__ . '/vendor/autoload_runtime.php';

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[Route(path: '/', name: 'home')]
    public function home(Request $request, LoggerInterface $logger): Response
    {
        /* dd($request->query->all()); */
        /* $logger->info('Homepage accessed', ['ip' => $request->getClientIp()]); */
        $name = $request->query->get('name', 'world');
        /* dd($name); */

        return new Response('<h1>Hello ' . htmlspecialchars($name) . '!<h1>');
    }

    #[Route(path: '/about', name: 'about')]
    public function about(): Response
    {
        return new Response('<h1>About</h1>');
    }
}

return function (array $context) {
    /* dd($context); */

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);

};
