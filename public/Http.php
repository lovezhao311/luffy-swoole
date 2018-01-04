<?php
namespace luffyzhao;

include_once '../vendor/autoload.php';

use luffyzhao\librarys\server\Http;

$server = new Http(Config::get('http'));
$server->start();

//实例说明
// 运行方式：
//      php Http.php
//      加载config/http.php配置文件
//      访问 127.0.0.1/index/index 会根据跌幅跳转到 app\http\Index 控制器的 index 方法
//      public function index()
//      {
//          $this->after('index/index', 1000);
//      }
//      方法说明 生成一个after定时器 在1000ms后执行 app\after\index::index方法
