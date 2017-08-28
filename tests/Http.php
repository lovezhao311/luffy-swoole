<?php
include_once '../vendor/autoload.php';

use luffyzhao\App;
use luffyzhao\librarys\server\Http;
use luffyzhao\Config;

$http = Config::get('http');

$app = App::instance();
$app->setServer(new Http($http['host'], $http['port'],$http['mode'],$http['sockType']));
$app->setConfig($http['set']);
$app->start();
