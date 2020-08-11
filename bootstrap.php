<?php

use App\Provider\AppProvider;
use App\Provider\ConsoleCommandProvider;
use App\Provider\DoctrineOrmProvider;
use App\Provider\RenderProvider;
use App\Provider\WebProvider;
use App\Support\Config;
use App\Support\ServiceProviderInterface;
use Symfony\Component\Dotenv\Dotenv;
use UltraLite\Container\Container;

require_once __DIR__ . '/vendor/autoload.php';

(new Dotenv())->loadEnv(__DIR__ . '/.env');
$env = getenv('APP_ENV') ?? 'dev';

$config = new Config(__DIR__ . '/config', $env, __DIR__);

$providers = [
    AppProvider::class,
    DoctrineOrmProvider::class,
    ConsoleCommandProvider::class,
    WebProvider::class,
    RenderProvider::class,
];

$container = new Container([
    Config::class => static function () use ($config) { return $config; },
]);

foreach ($providers as $providerClassName) {
    if (class_exists($providerClassName)) {
        if (($provider = new $providerClassName) instanceof ServiceProviderInterface) {
            $provider->register($container);
        } else {
            throw  new RuntimeException(sprintf('%s class is not a Service Provider', $providerClassName));
        }
    } else {
        throw  new RuntimeException(sprintf('Provider %s not found', $providerClassName));
    }
}

return $container;
