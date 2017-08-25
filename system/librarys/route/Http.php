<?php
namespace luffyzhao\librarys\route;

use luffyzhao\abstracts\Route;
use Swoole\Http\Request;

class Http extends Route
{
    protected $method = 'http';

    protected $files = [];

    protected function __construct(Request $request)
    {
        $data = [];

        if ($request->server['request_method'] == 'GET') {
            $data['data'] = isset($request->get) ? $request->get : [];
        } else {
            $data['data'] = isset($request->post) ? $request->post : [];
        }
        // 文件
        $this->files = isset($request->files) ? $request->files : [];

        $uri = isset($request->server['request_uri']) ? $request->server['request_uri'] : $request->server['path_info'];
        if ('/index.php' == $uri) {
            $this->resolve($data);
        } else if ($uri == '/favicon.ico') {
            throw new \Exception("favicon.ico");
        } else {
            $request = explode('/', trim($uri, '/'), 3);

            $data['route'] = [
                'controller' => isset($request[0]) ? $request[0] : null,
                'action' => isset($request[1]) ? $request[1] : null,
            ];

            $this->resolve($data);
        }
    }
    /**
     * 获取文件
     * @return [type] [description]
     */
    public function getFiles()
    {
        return $this->files;
    }
    /**
     * 获取路由参数
     * @method   getRoute
     * @DateTime 2017-08-22T16:17:33+0800
     * @return   [type]                   [description]
     */
    public function getRoute()
    {
        return $this->route;
    }
    /**
     * 获取请求参数(请求内容)
     * @method   getData
     * @DateTime 2017-08-22T16:17:46+0800
     * @return   [type]                   [description]
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * 获取请求方式
     * @method   getMethod
     * @DateTime 2017-08-22T16:21:59+0800
     * @return   [type]                   [description]
     */
    public function getMethod()
    {
        return $this->method;
    }
}
