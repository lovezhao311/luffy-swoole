<?php
namespace app\http;

use luffyzhao\abstracts\Core;
use luffyzhao\Config;
use luffyzhao\db\Db;

/**
 *
 */
class Index extends Core
{
    public function index()
    {
        $find = $this->getDb()->name('base_client_driver')->findAll();
        return json_encode($find);
    }
}
