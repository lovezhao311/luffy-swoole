<?php
namespace app\http;

use luffyzhao\abstracts\Core;

/**
 *
 */
class Index extends Core
{
    public function index()
    {
        $this->task('index/index');
        return "hello swoole";
    }
}
