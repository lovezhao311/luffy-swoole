<?php
namespace app\after;

use luffyzhao\abstracts\Core;

/**
 *
 */
class Index extends Core
{
    public function index()
    {
        echo "index:after " . time() . " \n";
    }

    public function index2()
    {
        echo "index2:after " . time() . " \n";
    }
}
