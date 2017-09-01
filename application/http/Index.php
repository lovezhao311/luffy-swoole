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
        $db = App::getDb();
        $db->startTrans();
        $db->execute("INSERT INTO `test_db` (`name`,`phone`) VALUES (:name, :phone)", ['name' => '黄四娘家', 'phone' => '18545214125']);
        sleep(10);
        $id = $db->getLastInsID();
        $db->rollback();
        return $id;
    }

    public function update()
    {
        $db = App::getDb();
        $db->execute("INSERT INTO `test_db` (`name`,`phone`) VALUES (:name, :phone)", ['name' => '黄四娘家', 'phone' => '18545214125']);
        return $db->getLastInsID();
    }
}
