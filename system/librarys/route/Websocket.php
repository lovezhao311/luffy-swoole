<?php
namespace luffyzhao\librarys\route;

use luffyzhao\abstracts\Route;

class Websocket extends Route
{
    /**
     * @var object 对象实例
     */
    protected static $instance = null;

    protected $method = 'websocket';

}
