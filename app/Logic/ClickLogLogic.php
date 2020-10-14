<?php


namespace App\Logic;


use App\Models\ClickLogModel;
use App\Utils\Singleton;

class ClickLogLogic
{
    use Singleton;

    /**
     * 记录点击
     * @param $params
     * @return int
     */
    public function addClickLog($params)
    {
        return ClickLogModel::getInstance()->insertGetId($params);

    }

}
