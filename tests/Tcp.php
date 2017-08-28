<?php
include_once '../vendor/autoload.php';

use luffyzhao\App;
use luffyzhao\librarys\server\Tcp;

$app = App::instance();
$app->setServer(new Tcp);
$app->setConfig(['log_file' => 's.log']);
$app->start();
