<?php
namespace app\http;

use luffyzhao\abstracts\Core;

/**
 *
 */
class Reload extends Core
{
    public function worker()
    {
        $this->getApp()->reload();
        return '重启成功';
    }
}
