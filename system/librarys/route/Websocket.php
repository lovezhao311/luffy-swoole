<?php
namespace luffyzhao\librarys\route;

use luffyzhao\abstracts\Route;
use luffyzhao\interfaces\Route as RouteInterface;

class Websocket extends Route implements RouteInterface
{
    protected $method = 'websocket';

}
