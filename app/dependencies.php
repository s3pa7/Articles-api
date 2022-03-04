<?php


declare(strict_types=1);

use Slim\App;
use App\Lib\Database\DB;
use Slim\Factory\AppFactory;
use Psr\Container\ContainerInterface;

return [
    App::class => function (ContainerInterface $container) {
        return AppFactory::createFromContainer($container);
    },
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },
    'db' => function (ContainerInterface $container) {
        return DB::getInstance($container->get('settings')['db']);
    },



];
