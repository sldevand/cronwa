<?php

use Slim\Http\Request;
use Slim\Http\Response;

require_once '../vendor/autoload.php';

/** @var \Slim\App $app */
$app = new \Slim\App;
$container = $app->getContainer();
$container['crontasks'] = function($c) {
  return new \App\CronTasks();
};

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(
        '../templates', [
        'cache' => false
    ]);

     return $view;
};


$container->crontasks->fetchFromFile("../crontabs/crontab.camera.txt");

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'tasks.html.twig', ['tasks' => $this->crontasks->getTasks()]);;
});

$app->run();
