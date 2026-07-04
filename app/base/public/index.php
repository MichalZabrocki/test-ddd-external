<?php

declare(strict_types=1);

use App\Base\Kernel;

require_once dirname(__DIR__) . '/../../vendor/autoload_runtime.php';

return static function (array $context) {
    return new \App\Base\Kernel($context['APP_ENV'], (bool)$context['APP_DEBUG']);
};


//
//use App\Kernel;
//use Symfony\Component\ErrorHandler\Debug;
//use Symfony\Component\HttpFoundation\Request;
//
//require dirname(__DIR__) . '/../../bootstrap.php';
//
//if ($_SERVER['APP_DEBUG']) {
//	umask(0000);
//
//	Debug::enable();
//}
//
//if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
//	Request::setTrustedProxies(
//		explode(',', $trustedProxies),
//		Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO
//	);
//}
//
//if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
//	Request::setTrustedHosts([$trustedHosts]);
//}
//
//$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
//$request = Request::createFromGlobals();
//$response = $kernel->handle($request);
//$response->send();
//$kernel->terminate($request, $response);
