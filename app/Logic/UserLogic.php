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

        $data = [];
        if (empty($result)) {
            CommonUtil::throwException(1, '用户信息不存在');
        }

        $data['is_member'] = (empty($result['expire_time'])) ? 0 : ($result['expire_time'] > time() ? 1 : 0);
        $data['expire_time'] = (empty($result['expire_time'])) ? '-' : date('Y年m月d日', $result['expire_time']);
        $data['user_type'] = $result['user_type'];
        $data['user_money'] = $result['user_money'] / 100;
        return $data;
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
