<?php
include_once '../vendor/autoload.php';

use luffyzhao\Config;
use luffyzhao\librarys\server\Http;

$server = new Http();
$http = Config::get('http');
$server->serverSet($http['set']);
$server->start($http['host'], $http['port'], $http['mode'], $http['sockType']);
