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
$container['view'] = new \Slim\Views\PhpRenderer('../templates/');

$cronJob = new \App\CronJob();
$cronJob->parse("* * * * * ls");
$cronJob->setName('toto');

$container->get('crontasks')->addTask($cronJob);

$app->get('/', function (Request $request, Response $response, array $args) {
    $response = $this->view->render($response, 'tasks.phtml', ['tasks' => $this->crontasks->getTasks()]);
    return $response;
});

$app->run();
