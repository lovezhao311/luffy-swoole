<?php
namespace app\http;

use luffyzhao\abstracts\Core;
use luffyzhao\App;

/**
 *
 */
class Index extends Core
{
    public function index()
    {
        $this->after('index/index', 1000, []);
    }

}
