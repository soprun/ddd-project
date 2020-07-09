<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if (false === empty($_SERVER['TRUSTED_PROXIES'])) {
    Request::setTrustedProxies(
        explode(',', $_SERVER['TRUSTED_PROXIES']),
        Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST
    );
}

if (false === empty($_SERVER['TRUSTED_HOSTS'])) {
    Request::setTrustedHosts([
        $_SERVER['TRUSTED_HOSTS'],
    ]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
