<?php
include_once '../vendor/autoload.php';

use luffyzhao\App;
use luffyzhao\librarys\server\Http;

$app = App::instance();
$app->setServer(new Http);
$app->setConfig([]);
$app->start();
