<?php

use App\Cron\CronTab;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response, array $args) {

    return $this->view->render($response, 'crontabs.html.twig', ['crontabs' => $this->crontabs->getCronTabs()]);
});

$app->get('/edit/{tab}/{job}', function (Request $request, Response $response, array $args) {
    $tab = $args['tab'];
    $job = $args['job'];

    /** @var CronTab $cronTab */
    $cronTab = $this->crontabs->getCronTab($tab);
    $job = $cronTab->getJob($job);

    return $this->view->render($response, 'crontab-form.html.twig', ['cronTab' => $cronTab,'job' => $job]);
});

$app->post('/{tab}', function (Request $request, Response $response, array $args) {

    $tab = $args['tab'];
    $body = $request->getParsedBody();



    var_dump($body);
    die;

    return $this->view->render($response, 'crontab-edit.html.twig', ['name' => $name]);
});

$app->put('/', function (Request $request, Response $response, array $args) {

    $name = $args['name'];

    return $this->view->render($response, 'crontab-edit.html.twig', ['name' => $name]);
});

$app->delete('/{name}', function (Request $request, Response $response, array $args) {

    $name = $args['name'];

    return $this->view->render($response, 'crontab-edit.html.twig', ['name' => $name]);
});