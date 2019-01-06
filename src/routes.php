<?php

use App\Cron\CronTab;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


$app->group('/cronwa/', function (App $app) {

    $app->get('', function (Request $request, Response $response, array $args) {

        $message = '';
        $messages = $this->flash->getMessage('flash');
        if (is_array($messages) && count($messages) > 0) {
            $message = $messages[0];
        }

        return $this->twig->render(
            $response, 'crontabs.html.twig',
            [
                'crontabs' => $this->crontabs->getCronTabs(),
                'message' => $message
            ]
        );
    });

    $app->get('edit/{tab}/{job}', function (Request $request, Response $response, array $args) {
        $tab = $args['tab'];
        $job = $args['job'];

        /** @var CronTab $cronTab */
        $cronTab = $this->crontabs->getCronTab($tab);
        $job = $cronTab->getJob($job);

        return $this->twig->render($response, 'crontab-form.html.twig', ['cronTab' => $cronTab, 'job' => $job]);
    });

    $app->map(['POST', 'DELETE'], '{name}', function (Request $request, Response $response, array $args) {

        if ($request->isPost()) {
            $body = $request->getParsedBody();

            //TODO Here run the validation and persistence in file


            $this->flash->addMessage('flash', 'Fichier enregistré !');
        }

        return $response->withRedirect('/cronwa/', 302);
    });
});


