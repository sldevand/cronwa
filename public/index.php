<?php

use Slim\Http\Request;
use Slim\Http\Response;

require_once '../vendor/autoload.php';

/** @var \Slim\App $app */
$app = new \Slim\App;
$container = $app->getContainer();
$container['crontabs'] = function($c) {
  return new \App\CronTabs();
};

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(
        '../templates', [
        'cache' => false
    ]);

     return $view;
};

$container->crontabs->fetchFromDirectory('../crontabs');

$app->get('/', function (Request $request, Response $response, array $args) {
    $cronTabs = $this->crontabs->getCronTabs();

    return $this->view->render($response, 'crontabs.html.twig', ['crontabs' => $cronTabs]);
});

$app->get('/edit/{name}', function (Request $request, Response $response, array $args) {

    $name = $args['name'];

    return $this->view->render($response, 'crontab-edit.html.twig', ['name' => $name]);
});



$app->run();
