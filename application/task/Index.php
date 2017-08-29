<?php
namespace app\task;

use luffyzhao\abstracts\Core;

/**
 *
 */
class Index extends Core
{

    public function index()
    {
        $db = $this->getDb();
        //插入数据
        $res = $db->table('test_db')->data('name', '战非')->data('phone', '15215214578')->insert();
        // 没有数据
        $res = $db->table('test_db')->where('phone', '=', '15215214578')->limit(1)->findAll();
        // 有数据 (事务开始之后拿主库里的数据)
        $db->startTrans();
        $res1 = $db->table('test_db')->where('phone', '=', '15215214578')->limit(1)->findAll();
        $db->commit();
        return json_encode($res) . "\n" . json_encode($res1);
    }
}
