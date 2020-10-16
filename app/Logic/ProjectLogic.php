<?php


namespace App\Logic;


use App\Models\ProjectModel;
use App\Utils\Singleton;

class ProjectLogic
{
    use Singleton;

    /**
     * 获取列表
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function getProject($page, $pageSize)
    {
        $result = ProjectModel::getInstance()->getListData($page, $pageSize);
        $list = $result['list'];
        if (count($list) > 0) {
            foreach ($list as $key => $value) {
                $list[$key]->image = env('IMAGE_URL') . $value->image;
                $list[$key]->share_image = env('IMAGE_URL') . $value->share_image;
            }
            $result['list'] = $list;
        }
        return $result;

    }

}
