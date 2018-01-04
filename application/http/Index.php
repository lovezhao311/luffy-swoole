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
        $this->after('index/index', 1000);
    }

    public function index2()
    {
        $this->after('index/index2', 2000);
    }
}
