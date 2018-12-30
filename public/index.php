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

/** @var \App\CronJob*/
$cronJob = new \App\CronJob();
$cronJob->parse("* * * * * ls");
$cronJob->setName('toto');

/** @var \App\CronJob*/
$cronJob2 = new \App\CronJob();
$cronJob2->parse("* * * * * ls");
$cronJob2->setName('tata');

$container->get('crontasks')->addTask($cronJob)->addTask($cronJob2);

$app->get('/', function (Request $request, Response $response, array $args) {
    $response = $this->view->render($response, 'tasks.html.twig', ['tasks' => $this->crontasks->getTasks()]);
    return $response;
});

$app->run();
