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
        $this->task('index/index');
        $this->after('index/index', 1000);
        return time() . " i'm http-1\n";
    }
}
