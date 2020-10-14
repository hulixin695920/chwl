<?php

namespace App\Console\Commands;

use App\Models\UserModel;
use EasyWeChat\Factory;
use Illuminate\Console\Command;
use Tests\Models\User;

class SubscribeMsgCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:subscribe:msg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送订阅消息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = config('wechat.mini_program.default');
        $app = Factory::miniProgram($config);


        //查找订阅的用户

        $user = UserModel::getInstance()->where('msg_subscible', 1)->get()->toArray();
        if (!empty($user)) {
            foreach ($user as $key => $value) {
                //发送订阅消息
                $data = [
                    'template_id' => 'qJohCVdOyxpFMzLUe8qOQpK9lglXZcnxLmpXSo4Hs3s', // 所需下发的订阅模板id
                    'touser' => get_property($value, 'openid', ''),     // 接收者（用户）的 openid
                    'page' => '/pages/index/index',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
                    'data' => [         // 模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
                        'thing1' => [
                            'value' => get_property($value, 'nickname', '尊敬的用户'),
                        ],
                        'thing2' => [
                            'value' => '优惠券领取',
                        ],
                        'time3' => [
                            'value' => date('Y-m-d H:i:s')
                        ],
                        'thing4' => [
                            'value' => '点击进入小程序，进行优惠券领取',
                        ],
                    ],
                ];
                $app->subscribe_message->send($data);
            }
        }


    }
}
