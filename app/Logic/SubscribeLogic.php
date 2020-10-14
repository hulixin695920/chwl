<?php


namespace App\Logic;


use App\Models\SubscribeModel;
use App\Utils\Singleton;

class SubscribeLogic
{
    use Singleton;


    /**
     * 添加订阅记录
     * @param $params
     * @return int
     */
    public function addSubscribe($params)
    {
        return SubscribeModel::getInstance()->insertGetId($params);

    }

}
