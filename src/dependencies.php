<?php

$container = $app->getContainer();
$container['crontabs'] = function ($c) {
    $cronTabs = new \App\Cron\CronTabs();
    $cronTabs->fetchFromDirectory(__DIR__ . '/../crontabs');

    return $cronTabs;
};

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(
        '../templates', [
        'cache' => false
    ]);

    return $view;
};

$container['logger'] = function ($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};


