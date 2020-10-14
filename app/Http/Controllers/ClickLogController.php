<?php


namespace App\Http\Controllers;


use App\Logic\ClickLogLogic;
use App\Utils\CommonUtil;
use Illuminate\Http\Request;

class ClickLogController extends Controller
{
    /**
     * 添加点击记录
     * @param Request $request
     * @return int
     * @throws \App\Exceptions\ApiException
     */
    public function addClickLog(Request $request)
    {
        $params['user_id'] = $request->input('userId', 0);
        $params['app_id'] = $request->input('app_id', '');
        $params['project_id'] = $request->input('project_id', '');
        $params['remark'] = $request->input('remark', '系统记录');

        foreach ($params as $key => $value) {
            if (empty($value)) {
                CommonUtil::throwException(1, '参数错误');
            }
        }

        return ClickLogLogic::getInstance()->addClickLog($params);

    }

}
