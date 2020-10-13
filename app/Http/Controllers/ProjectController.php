<?php


namespace App\Http\Controllers;


use App\Logic\ProjectLogic;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * 获取列表
     * @param Request $request
     * @return array
     */
    public function listData(Request $request)
    {
        $page = $request->input('page',1);
        $pageSize = $request->input('pageSize',10);

        return  ProjectLogic::getInstance()->getProject($page, $pageSize);
    }
}
