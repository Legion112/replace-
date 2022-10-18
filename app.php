#!/usr/bin/env php
<?php
declare(strict_types=1);

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

require_once 'vendor/autoload.php';

(static function (): void {
    $container = (static function(): ContainerInterface{
        $fileLocator = new FileLocator(__DIR__);
        $containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader($containerBuilder, $fileLocator);
        $loader->load('services.yaml');
        $containerBuilder->compile();
        return $containerBuilder;
    })();
    $input = new ArgvInput();
    $output = new ConsoleOutput();
    $application = $container->get(Application::class);
    /** @noinspection NullPointerExceptionInspection */
    $application->run($input, $output);
})();