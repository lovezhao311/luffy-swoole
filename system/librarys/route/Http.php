<?php
namespace luffyzhao\librarys\route;

use luffyzhao\abstracts\Route;
use Swoole\Http\Request;

class Http extends Route
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
}
