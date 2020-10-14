<?php


namespace App\Logic;


use App\Models\SubscribeModel;
use App\Models\UserModel;
use App\Utils\CommonUtil;
use App\Utils\Singleton;

class SubscribeLogic
{
    use Singleton;


    /**
     * 添加订阅记录
     * @param $params
     * @return int
     * @throws \App\Exceptions\ApiException
     */
    public function addSubscribe($params)
    {
        // 先查询是否有该记录
        $result = SubscribeModel::getInstance()->where('user_id', $params['user_id'])->first();
        if (empty($result)) {
            $id = SubscribeModel::getInstance()->insertGetId($params);
            if (!$id) {
                CommonUtil::throwException(1, '系统异常');
            }
        } else {
            $effs = SubscribeModel::getInstance()->where('user_id', $params['user_id'])->update(['status' => $params['status']]);
            if ($effs == false) {
                CommonUtil::throwException(1, '系统异常');
            }
        }
        // 更新用户表
        return UserModel::getInstance()->where('id', $params['user_id'])->update(['msg_subscible' => $params['status']]);


    }

}
