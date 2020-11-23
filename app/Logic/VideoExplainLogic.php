<?php


namespace App\Logic;


use App\Utils\CommonUtil;
use App\Utils\Singleton;
use Smalls\VideoTools\VideoManager;

class VideoExplainLogic
{
    use Singleton;

    /**
     * 短视频去水印
     * @param $url
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function explain($url)
    {
        try {
            if (strpos($url, "douyin.com") || strpos($url, "iesdouyin.com")) {
                $result = VideoManager::DouYin()->start($url);
            } elseif (strpos($url, "huoshan.com")) {
                $result = VideoManager::HuoShan()->start($url);
            } elseif (strpos($url, "ziyang.m.kspkg.com") || strpos($url, "kuaishou.com") || strpos($url, "gifshow.com") || strpos($url, "chenzhongtech.com")) {
                $result = VideoManager::KuaiShou()->start($url);
            } elseif (strpos($url, "www.pearvideo.com")) {
                $result = VideoManager::LiVideo()->start($url);
            } elseif (strpos($url, "www.meipai.com")) {
                $result = VideoManager::MeiPai()->start($url);
            } elseif (strpos($url, "immomo.com")) {
                $result = VideoManager::MoMo()->start($url);
            } elseif (strpos($url, "ippzone.com")) {
                $result = VideoManager::PiPiGaoXiao()->start($url);
            } elseif (strpos($url, "pipix.com")) {
                $result = VideoManager::PiPiXia()->start($url);
            } elseif (strpos($url, "longxia.music.xiaomi.com")) {
                $result = VideoManager::QuanMingGaoXiao()->start($url);
            } elseif (strpos($url, "shua8cn.com")) {
                $result = VideoManager::ShuaBao()->start($url);
            } elseif (strpos($url, "toutiaoimg.com") || strpos($url, "toutiaoimg.cn")) {
                $result = VideoManager::TouTiao()->start($url);
            } elseif (strpos($url, "weishi.qq.com")) {
                $result = VideoManager::WeiShi()->start($url);
            } elseif (strpos($url, "mobile.xiaokaxiu.com")) {
                $result = VideoManager::XiaoKaXiu()->start($url);
            } elseif (strpos($url, "xigua.com")) {
                $result = VideoManager::XiGua()->start($url);
            } elseif (strpos($url, "izuiyou.com")) {
                $result = VideoManager::ZuiYou()->start($url);
            } elseif (strpos($url, "weibo.com")) {
                $result = VideoManager::WeiBo()->start($url);
            } else {
                CommonUtil::throwException(1, '链接非法');
            }
            if (!$result) {
                CommonUtil::throwException(1, '链接错误');
            }
            return $result;
        } catch (\Exception $e) {
            CommonUtil::throwException(1, $e->getMessage());
        }
    }

}
