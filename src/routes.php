<?php

use App\Controller\CronJobController;
use App\Controller\CronTabsController;
use Slim\App;
use DavidePastore\Slim\Validation\Validation;
use Respect\Validation\Validator as v;

$app->group('/cronwa/', function (App $app) {

    $app->get('', CronTabsController::class . ':home')->setName("home");
    $app->get('edit/{tab}/{job}', CronJobController::class . ':edit');
    $app->get('add/{tab}', CronJobController::class . ':edit');


    $usernameValidator = v::alnum()->length(4, 80);
    $validators = array(
        'name' => $usernameValidator
    );

    $app->post('{name}', CronJobController::class . ':post')->add(new Validation($validators));
});
