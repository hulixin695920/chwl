<?php

namespace App\Http\Middleware;


use App\ConstDir\BaseConst;
use App\Exceptions\ApiException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AfterMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($response->exception != null && ($response->exception instanceof ApiException)
        ) {
            //抛异常
            $data = [
                'code' => $response->exception->getCode(),
                'msg' => $response->exception->getMessage(),
                //'data' => [],
            ];
            $response->setStatusCode(Response::HTTP_OK);
        } elseif ($response->exception != null && $response->exception instanceof \Exception) {
            //根据环境变量 判断
            if (env('APP_ENV') == 'production') {
                $data = [
                    'code' => BaseConst::ERROR_CODE,
                    'msg' => BaseConst::ERROR_CODE_MSG,
                    //'data' => [],
                ];
                $response->setStatusCode(Response::HTTP_OK);
                //记录错误日志::系统级异常
            }
        } else {
            $data = [
                'code' => BaseConst::SUCCESS_CODE,
                'msg' => BaseConst::SUCCESS_CODE_MSG,
                'nowTime' => time()
            ];

            //兼容老逻辑
            $origin = $response->getOriginalContent();
            if (!empty($origin) && (is_array($origin) || is_object($origin))) {
                $origin = $this->_filter($origin);
            }

            if (empty($origin)) {
                $origin = new \stdClass();
            } else {
                //兼容IOS2.9.1
                if (isset($origin['shareQRUrl'])) {
                    $data['shareQRUrl'] = $origin['shareQRUrl'];
                }
            }

            $data['data'] = $origin;
        }
        //头信息为200的时候正常输出
        if ($response->getStatusCode() == Response::HTTP_OK) {
            //todo jsonp格式 由于jsonp存在安全问题 建议尽量不要用jsonp
            $response->setContent(json_encode($data));
            $response->setProtocolVersion('1.1');
            $response->setCharset('utf-8');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
            $response->headers->set('Access-Control-Allow-Headers', 'x-requested-with,content-type');

        }

        //todo 日志  待定
        return $response;
    }


    /**
     * 因安卓和IOS数据为null,导致崩溃
     * 过滤数据为null
     * @param $data
     * @return array $data
     */
    private function _filter($data)
    {

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = $this->_filter($v);
            } else {
                if (!isset($v)) {
                    unset($data[$k]);
                }
            }
        }
        return $data;
    }
}
