<?php


namespace App\Http\Controllers;


use App\Logic\VideoExplainLogic;
use App\Utils\CommonUtil;
use Illuminate\Http\Request;

class VideoExplainController extends Controller
{
    public function index(Request $request)
    {
        $url = $request->input('url');

        //验证url
        if (empty($url)) {
            CommonUtil::throwException(1, 'url有误');
        }
        return VideoExplainLogic::getInstance()->explain($url);
    }

}
