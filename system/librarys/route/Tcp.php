<?php
namespace luffyzhao\librarys\route;

use luffyzhao\abstracts\Route;
use luffyzhao\interfaces\Route as RouteInterface;

class Tcp extends Route implements RouteInterface
{
    protected $method = 'tcp';

}
