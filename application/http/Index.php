<?php
namespace app\http;

use luffyzhao\App;

/**
 *
 */
class Index
{
    public function index()
    {
        $server = App::instance()->getServer();
        $server->task('');
        $server->after(1000, function () {
            echo time() . " i'm after-1\n";
        });
        return time() . " i'm http-1\n";
    }
}
