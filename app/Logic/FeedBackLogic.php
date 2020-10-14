<?php


namespace App\Logic;


use App\Models\FeedBackModel;
use App\Utils\Singleton;

class FeedBackLogic
{
    use Singleton;

    /**
     * 添加内容
     * @param $userId
     * @param $openId
     * @param $value
     * @param $img
     * @return int
     */
    public function addFeedBack($userId, $openId, $value,$img)
    {
        $data['user_id'] = $userId;
        $data['openid'] = $openId;
        $data['msg_content'] = $value;
        $data['img_content'] = $img;

        return FeedBackModel::getInstance()->insertGetId($data);


    }

}