<?php

use App\Cron\CronTab;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


$app->group('/cronwa/', function (App $app) {

    $app->get('', function (Request $request, Response $response, array $args) {


        return $this->view->render($response, 'crontabs.html.twig', ['crontabs' => $this->crontabs->getCronTabs()]);
    });

    $app->get('edit/{tab}/{job}', function (Request $request, Response $response, array $args) {
        $tab = $args['tab'];
        $job = $args['job'];

        /** @var CronTab $cronTab */
        $cronTab = $this->crontabs->getCronTab($tab);
        $job = $cronTab->getJob($job);

        return $this->view->render($response, 'crontab-form.html.twig', ['cronTab' => $cronTab, 'job' => $job]);
    });

    $app->map(['POST', 'DELETE'], '/{name}', function (Request $request, Response $response, array $args) {

        if ($request->isPost()) {
            $body = $request->getParsedBody();

            return $this->view->render($response, 'crontab-edit.html.twig', ['name' => $body]);
        }

        return $this->view->render($response, 'crontabs.html.twig', ['crontabs' => $this->crontabs->getCronTabs()]);
    });
});


