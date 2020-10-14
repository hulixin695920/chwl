<?php


namespace App\Models;


use App\Utils\Singleton;

class ClickLogModel extends BaseModel
{
    use Singleton;
    protected $table = 't_click_log';

}
