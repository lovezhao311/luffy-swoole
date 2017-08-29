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
        $find = $this->getDb()->name('base_client_driver')->where('id','=',250)->findAll();
        $find1 = $this->getDb()->name('order_details')->where('id','=',78716)->findAll();
        return json_encode($find1);
    }
}
