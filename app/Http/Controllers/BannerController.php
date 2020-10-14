<?php


namespace App\Http\Controllers;


use App\Logic\BannerLogic;

class BannerController extends Controller
{
    /**
     * 获取banner
     * @return array
     */
    public function bannerList()
    {
        return BannerLogic::getInstance()->getBannerList();
    }

}
