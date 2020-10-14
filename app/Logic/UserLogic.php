<?php


namespace App\Logic;


use App\Models\UserModel;
use App\Utils\CommonUtil;
use App\Utils\Singleton;

class UserLogic
{
    use Singleton;

    /**
     * 获取用户信息
     * @param $userId
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function getUserInfo($userId)
    {
        $result = UserModel::getInstance()->getOneRecord($userId);

        if (empty($result)) {
            CommonUtil::throwException(1, '用户信息不存在');
        }
        return $result;
    }

    /**
     * 保存或者更新
     * @param $params
     * @return bool
     */
    public function saveUser($params)
    {
        return UserModel::getInstance()->saveData($params);
    }


}
