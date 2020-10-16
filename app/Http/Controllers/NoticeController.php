<?php


namespace App\Http\Controllers;


use App\Logic\NoticeLogic;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * 获取通知信息
     * @param Request $request
     * @return array
     */
    public function getNotice(Request $request)
    {
        return NoticeLogic::getInstance()->getNotice();

    }

}
