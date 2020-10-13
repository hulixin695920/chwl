<?php


namespace App\Models;

use App\Utils\Singleton;

class ProjectModel extends BaseModel
{

    use Singleton;
    protected $table = 't_project';


    /**
     * 获取列表
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function getListData($page, $pageSize)
    {
        $isEnd = 0;
        $offset = ($page * $pageSize) - $pageSize;
        $order = ['created_at' => 'desc'];
        $column = ['app_id', 'title', 'created_at', 'description', 'image', 'url','path'];
        $where['status'] = 1;
        $list = $this->getList($column, $where, $order, null, $pageSize, $offset);
        if (count($list) < $pageSize) {
            $isEnd = 1;
        }
        return ['list' => $list, 'page' => (int)$page, 'isEnd' => $isEnd];

    }
}
