<?php

use App\Kernel;
use App\CacheKernel;
use Symfony\Component\HttpFoundation\Request;



require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);

if ('prod' === $kernel->getEnvironment()) {
    $kernel = new CacheKernel($kernel);
}

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
