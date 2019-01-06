<?php
/**
 * @var \Slim\Container $container
 */

use App\Controller\CronJobController;

$container = $app->getContainer();

/**
 * @param \Slim\Container $c
 * @return \App\Cron\CronTabs
 */
$container['crontabs'] = function ($c) {
    $cronTabs = new \App\Cron\CronTabs();
    $cronTabs->fetchFromDirectory(__DIR__ . '/../crontabs');

    return $cronTabs;
};

/**
 * @return \Slim\Flash\Messages
 */
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

/** @param \Slim\Container $c
 * @return \Slim\Views\Twig
 */
$container['twig'] = function ($c) {
    $twig = new \Slim\Views\Twig(
        __DIR__ . '/../templates', [
        'cache' => false
    ]);

    return $twig;
};

/**
 * @param \Slim\Container $c
 * @return \Monolog\Logger
 */
$container['logger'] = function ($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/app.log');
    $logger->pushHandler($file_handler);

    return $logger;
};

/**
 * @param \Slim\Container $c
 * @return CronJobController
 */
$container['CronJobController'] = function ($c) {

    return new CronJobController($c);
};
