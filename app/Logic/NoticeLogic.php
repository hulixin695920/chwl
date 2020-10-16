<?php


namespace App\Logic;


use App\Models\NoticeModel;
use App\Utils\Singleton;

class NoticeLogic
{
    use Singleton;

    /**
     * 获取通知
     * @return array
     */
    public function getNotice()
    {
        $data = [];
        $result = NoticeModel::getInstance()->where('status', 1)->get()->toArray();
        if (!empty($result)) {
            $data = array_column($result, 'title');
        }
        return $data;
    }

}
