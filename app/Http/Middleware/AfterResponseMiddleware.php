<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Monolog\Logger;

class AfterResponseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * terminable中间键，在响应被送到浏览器之后执行
     * @param $request
     * @param $response
     */
    public function terminate($request, Response $response)
    {
        $res = $response->getOriginalContent();
        //记录异常日志
        if (isset($res['code']) && $res['code'] > 0 && $res['code'] != 900) {
            $this->toWriteRequestLog($request, $res);
        }
        if (isset($res['code'])  && $res['code'] == 900) {
            $this->toWrite900Log();
        }
    }

    /**
     * @param Request $request
     * @param array $response
     */
    private function toWriteRequestLog($request, $response)
    {
        $userinfo = app('DefaultUserinfo');

        $route = $request->getPathInfo();
        $routeArr = explode('/', $route);
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'NO_AGENT';
        $arr = [
            'serTime' => date('Y-m-d H:i:s'),
            'type' => 0,
            'module' => $routeArr[1],
            'version' => $routeArr[2],
            'className' => $routeArr[3],
            'route' => $route,
            'origin' => $this->getOrigin(),
            'content' => [
                'serverAddr' => $_SERVER['SERVER_ADDR'],
                'method' => $routeArr[4],
                'userAgent' => $agent,
                'referer' => $_SERVER['HTTP_REFERER'] ?? '',
                'userIp' => $request->getClientIp(),
                'userinfoId' => $userinfo->userinfoId,
                'requestData' => $request->all(),
                'requestUrl' => $request->url(),
                'retCode' => $response['code'] ?? '',
                'retMsg' => $response['msg'] ?? '',
                'retData' => $response['data'] ?? '',
            ],
        ];
        //写日志
        Logger::info('api_info', ['arr' => $arr]);
    }

    public function toWrite900Log()
    {
        Logger::info('info',['error']);
    }

    public function getOrigin()
    {
        //判断头部
        if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            $platform = 'wechat';
        } elseif (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'WptMessenger')) {
            $platform = 'app';
        } elseif (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Weibo') || isset($_POST['signed_request'])) {
            $platform = 'weibo';
        } else {
            if (isset($_COOKIE['platform'])) {
                $platform = $_COOKIE['platform'];
            } else {
                $platform = 'web';
            }
        }

        return $platform;
    }
}