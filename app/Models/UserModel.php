<?php

namespace App\Models;

use App\Utils\Singleton;
use Illuminate\Database\Eloquent\Model;

class UserModel extends BaseModel
{
    use Singleton;
    protected $table = 't_users';

    /**
     * 根据ID获取用户信息
     * @param $id
     * @return array
     */
    public function getOneRecord($id)
    {
        $result = $this->where('id', '=', $id)->first();
        return empty($result) ? [] : $result->toArray();
    }

    /**
     * 获取用户信息
     * @param $openId
     * @return array
     */
    public function getUserByOpenId($openId)
    {
        $result = $this->where('openid', '=', $openId)->first();
        return empty($result) ? [] : $result->toArray();
    }

    /**
     * 更新或者新增
     * @param array $params
     * @return bool
     */
    public function saveData($params)
    {
        $code = 10000;
        if (isset($params['openid'])) {
            $result = $this->where('openid', '=', $params['openid'])->first();
            if (empty($result)) {
                $maxId = UserModel::getInstance()->max('id');
                $params['code'] = $code + $maxId + 1;
                // 新增
                return $this->insertGetId($params);
            } else {
                $params['code'] = $result['id'] + $code;
                $res = $this->where('openid', $params['openid'])->update($params);
                if ($res !== false) {
                    return get_property($result, 'id', 0);
                }
            }
        }
    }


    /**
     * 更新用户信息
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateUser($id, $data)
    {
        $res = $this->where('id', $id)->update($data);
        if ($res !== false) {
            return true;
        }

        return false;

    }

    /**
     * 获取用户列表
     * @param $page
     * @param $pageSize
     * @return array|mixed
     */
    public function getUserList($page, $pageSize)
    {
        $column = ['id', 'is_member', 'expire_time'];
        $where['is_del'] = 0;
        $where['is_member'] = 1;

        $order = ['created_at' => 'asc'];
        $offset = ($page * $pageSize) - $pageSize;
        $user = $this->getList($column, $where, $order, null, $pageSize, $offset);

        return empty($user) ? [] : json_decode(json_encode($user), true);

    }


    /**
     * 获取邀请用户
     * @param $userId
     * @param $page
     * @param $pageSize
     * @return array|mixed
     */
    public function getChildUser($userId, $page, $pageSize)
    {
        $isEnd = 0;
        $offset = ($page * $pageSize) - $pageSize;
        $order = ['created_at' => 'desc'];
        $column = ['mobile_phone', 'nickname', 'avatar', 'created_at'];
        $where['parent_id'] = $userId;

        $user = $this->getList($column, $where, $order, null, $pageSize, $offset);

        if (count($user) < $pageSize) {
            $isEnd = 1;
        }

        return ['list' => $user, 'page' => (int)$page, 'isEnd' => $isEnd];
    }


    /**
     * 新增测试用户
     * @param $params
     * @return int
     */
    public function saveDataTemp($params)
    {
        $code = 10000;
        $maxId = UserModel::getInstance()->max('id');
        $params['code'] = $code + $maxId + 1;
        // 新增
        return $this->insertGetId($params);

    }


}