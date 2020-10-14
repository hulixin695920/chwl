<?php


namespace App\Logic;


use App\Models\BannerModel;
use App\Utils\Singleton;

class BannerLogic
{
    use Singleton;

    /**
     * 获取banner
     * @return array
     */
    public function getBannerList()
    {
        $data = BannerModel::getInstance()->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]->image = env('IMAGE_URL') . $value->image;
        }

        return $data;
    }
}
