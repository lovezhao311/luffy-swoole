<?php
namespace luffyzhao\librarys\swoole;

use luffyzhao\abstracts\Swoole;
use luffyzhao\librarys\route\Http as HttpRoute;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 *
 */
class Http extends Swoole
{
    protected $processTitle = 'Http';

    protected $on = [
        'start',
        'shutdown',
        'workerStart',
        'workerStop',
        'task',
        'finish',
        'pipeMessage',
        'workerError',
        'managerStart',
        'managerStop',
        'request',
    ];
    /**
     * httpt请求时
     * @param  Request  $request  http请求对
     * @param  Response $response response
     * @return viod
     */
    public function onRequest(Request $request, Response $response)
    {
        ob_start();
        try {
            $route = new HttpRoute($request);
            $result = $route->run();
        } catch (\Exception $e) {
            switch ($e->getCode()) {
                case 404:
                    $response->status($e->getCode());
                    break;
                default:
                    $response->status(502);
            }
            $result = $e->getMessage();
        }
        $response->end($result);
        $this->obShow();
    }
}
