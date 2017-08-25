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
        echo "after " . time() . " \n";
    }
}
