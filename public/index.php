<?php
if (PHP_SAPI == 'cli-server') {
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

define("CRONTABS_PATH",__DIR__.'/../crontabs');

require __DIR__ . '/../vendor/autoload.php';
session_start();
$settings = require __DIR__ . '/../src/settings.php';
/** @var \Slim\App $app */
$app = new \Slim\App($settings);
require_once __DIR__ . '/../src/dependencies.php';
require_once __DIR__ . '/../src/middleware.php';
require_once __DIR__ . '/../src/routes.php';
$app->run();
