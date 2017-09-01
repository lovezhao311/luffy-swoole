<?php
namespace app\task;

use luffyzhao\abstracts\Core;
use luffyzhao\Debug;

/**
 *
 */
class Index extends Core
{

    public function index()
    {
        Debug::info('Task Index');
    }
}
