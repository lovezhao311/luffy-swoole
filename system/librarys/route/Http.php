<?php
namespace luffyzhao\librarys\route;

use luffyzhao\abstracts\Route;
use luffyzhao\Debug;
use Swoole\Http\Request;

class Http extends Route
{
    protected $method = 'http';

    protected $files = [];

    protected $post = [];

    protected $get = [];

    public function __construct(Request $request)
    {
        // post
        $this->post = isset($request->get) ? $request->get : [];
        // get
        $this->get = isset($request->post) ? $request->post : [];
        // 文件
        $this->files = isset($request->files) ? $request->files : [];

        $uri = isset($request->server['request_uri']) ? $request->server['request_uri'] : $request->server['path_info'];
        Debug::info($uri);

        if ('/index.php' == $uri) {
            $this->resolve([]);
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
     * [getGet description]
     * @return [type] [description]
     */
    public function getGet()
    {
        return $this->get;
    }
    /**
     * [getPost description]
     * @return [type] [description]
     */
    public function getPost()
    {
        return $this->post;
    }
}
