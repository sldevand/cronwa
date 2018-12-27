<?php

use App\CronJob;
use App\CronParser;

require_once __DIR__.'/vendor/autoload.php';

$cronJob = new CronJob();
$parser = new CronParser($cronJob);

var_dump($parser->parse(''));