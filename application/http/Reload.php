<?php
namespace app\http;

use luffyzhao\abstracts\Core;
use luffyzhao\App;

/**
 *
 */
class Reload extends Core
{
    public function worker()
    {
        App::getServer()->reload();
        return '重启成功';
    }
}
