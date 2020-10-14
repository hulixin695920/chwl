<?php


namespace App\Http\Controllers;


use App\Logic\SubscribeLogic;
use App\Utils\CommonUtil;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    /**
     * 新增订阅记录
     * @param Request $request
     * @return int
     * @throws \App\Exceptions\ApiException
     */
    public function addSubscribeLog(Request $request)
    {
        $params['user_id'] = $request->input('userId', 0);
        if (empty($params['user_id'])) {
            CommonUtil::throwException(1, '参数错误');
        }
        return SubscribeLogic::getInstance()->addSubscribe($params);
    }

}
