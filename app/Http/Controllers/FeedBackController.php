<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Logic\FeedBackLogic;
use App\Services\OSS;
use App\Utils\CommonUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedBackController extends Controller
{
    public function addFeedBack(Request $request)
    {
        $userId = $request->input('userId', 0);
        $openId = $request->input('openId', '');
        $value = $request->input('value', '');
        $image = $request->input('image', []);
        $upload = json_decode($image, true);

        if (empty($upload)) {
            $img = '{}';
        } else {
            $img = $image;
        }
        if (empty(trim($value))) {
            CommonUtil::throwException(1, '内容不能为空');
        }

        return FeedBackLogic::getInstance()->addFeedBack($userId, $openId, $value,$img);
    }


    /**
     * 上传文件
     * @param Request $request
     * @return string
     */
    public function uploadImage(Request $request)
    {
        $file = $request->file('file');

        $tmppath = $file->getRealPath();
        //生成文件名
        $fileName = $file->getFilename() . time() . date('ymd') . '.' . $file->getClientOriginalExtension();
        //拼接上传的文件夹路径(按照日期格式1810/17/xxxx.jpg)
        $pathName = 'feedback' . '/' . $fileName;
        OSS::publicUpload('lepindian', $pathName, $tmppath, ['ContentType' => $file->getClientMimeType()]);

        $url = OSS::getPublicObjectURL('lepindian', $pathName);
        Log::info('文件上传', ['orign' => $file, 'url' => $url]);

        return $url;
    }
}
