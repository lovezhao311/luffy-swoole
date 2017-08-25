<?php
namespace luffyzhao\librarys\route;

use luffyzhao\abstracts\Route;
use luffyzhao\interfaces\Route as RouteInterface;
use Swoole\Http\Request;

class Http extends Route implements RouteInterface
{
    protected $method = 'http';

    protected function __construct(Request $request)
    {
        $data = [];
        $data['data']['get'] = isset($request->get) ? $request->get : [];
        $data['data']['post'] = isset($request->post) ? $request->post : [];
        $data['data']['files'] = isset($request->files) ? $request->files : [];
        $uri = parse_url(isset($request->request_uri) ? $request->request_uri : '');

        if ('/index.php' == $uri['path']) {
            $this->resolve($data);
        } else {
            $request = explode('/', trim($uri['path'], '/'), 3);

            $data['route'] = [
                'controller' => isset($request[0]) ? $request[0] : null,
                'action' => isset($request[1]) ? $request[1] : null,
            ];

            $this->resolve($data);
        }
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
