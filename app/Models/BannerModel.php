<?php


namespace App\Models;


use App\Utils\Singleton;

class BannerModel extends BaseModel
{
    use Singleton;

    protected $table = 't_banners';

}
