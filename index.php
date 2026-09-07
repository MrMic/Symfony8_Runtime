<?php

use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/vendor/autoload_runtime.php';

return function () {

    return new Response('<h1>Hello World!</h1>');

};
