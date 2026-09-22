<?php

namespace App;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

final class PagesController
{
    #[Route(path: '/', name: 'home', methods: ['GET'])]
    public function home(
        Request $request,
        LoggerInterface $logger,
        Environment $twig
    ): Response {
        $name = $request->query->get('name', 'world');

        return new Response(
            $twig->render('home.html.twig', compact('name'))
        );
    }

    #[Route(path: '/about', name: 'about', methods: ['GET'])]
    public function about(Environment $twig): Response
    {
        return new Response($twig->render('about.html.twig'));
    }
}
