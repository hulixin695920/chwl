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

        preg_match_all("/http(s)?:[\/]{2}[a-z]+[.]{1}[a-z\d\-]+[.]{1}[a-z\d]*[\/]*[A-Za-z\d]*[\/]*[A-Za-z\d]*/",$url,$array2);

        if (!isset($array2[0]) || empty($array2[0])) {
            CommonUtil::throwException(1, 'url有误');
        }
//        print_r($array2[0][0]);die;
        return VideoExplainLogic::getInstance()->explain(trim($array2[0][0]));
    }

}
