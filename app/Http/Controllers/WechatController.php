<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\ContactModel;
use App\Services\OSS;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WechatController extends Controller
{
    public $app;
    public $miniContact;

    public function __construct()
    {
        $config = [
            'app_id' => env('WECHAT_MINI_PROGRAM_APPID'),
            'secret' => env('WECHAT_MINI_PROGRAM_SECRET'),

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => env('WECHAT_LOG_LEVEL', 'debug'),
                'file' => env('WECHAT_LOG_FILE', storage_path('logs/wechat-' . date('Y-m-d') . '.log')),
            ],
        ];
        $this->app = Factory::miniProgram($config);
    }

    /**
     * 公众号配置
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function serve()
    {
        $config = [
            'app_id' => env('WECHAT_APPID'),
            'secret' => env('WECHAT_SECRET'),
            'token' => env('TOKEN'),
            'response_type' => 'array',
            'aes_key' => env('WECHAT_AES_KEY', ''),
        ];
        $app = Factory::officialAccount($config);

        $app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'text':
                    if ($message['Content'] == '客服') {
                        return '当前客服繁忙，请添加客服微信"' . '"进行咨询';
                    }

                    break;
            }
        });

        // 转发收到的消息给客服
        $app->server->push(function ($message) {
            return new Transfer();
        });


        return $app->server->serve();
    }

    /**
     * 小程序
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function miniServe()
    {
        $config = [
            'app_id' => env('WECHAT_MINI_PROGRAM_APPID'),
            'secret' => env('WECHAT_MINI_PROGRAM_SECRET'),
            'token' => env('WECHAT_MINI_PROGRAM_TOKEN'),
            'response_type' => 'array',
            'aes_key' => env('WECHAT_MINI_PROGRAM_AES_KEY', ''),
        ];

        $this->app = Factory::miniProgram($config);
        $user = ContactModel::getInstance()->getOneRecord();
        $this->miniContact = get_property($user, 'user', '');

        $this->app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'text':
                    $content = '当前客服繁忙，请添加客服微信"' . $this->miniContact . '"进行咨询';
                    $this->app->customer_service->message($content)->to($message['FromUserName'])->send();
                    break;
            }
        });

        return $this->app->server->serve();
    }


    /**
     * 小程序商家
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function miniShopServe()
    {
        $config = [
            'app_id' => env('WECHAT_MINI_PROGRAM_APPID_SHOP'),
            'secret' => env('WECHAT_MINI_PROGRAM_SECRET_SHOP'),
            'token' => env('WECHAT_MINI_PROGRAM_SHOP_TOKEN'),
            'response_type' => 'array',
            'aes_key' => env('WECHAT_MINI_PROGRAM_SHOP_AES_KEY', ''),
        ];

        $this->app = Factory::miniProgram($config);
        $user = ContactModel::getInstance()->getOneRecord();
        $this->miniContact = get_property($user, 'user', '');

        $this->app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'text':
                    $content = '当前客服繁忙，请添加客服微信"' . $this->miniContact . '"进行咨询';
                    $this->app->customer_service->message($content)->to($message['FromUserName'])->send();
                    break;
            }
        });

        return $this->app->server->serve();
    }

    /**
     * 获取微信登录信息
     * @param Request $request
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function getUserBaseInfo(Request $request)
    {
        $code = $request->input('code');
        $result = $this->app->auth->session($code);
        Log::info('微信获取的信息', ['code' => $code, 'result' => $request]);
        return $result;

    }


    /**
     * 店铺获取信息
     * @param Request $request
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function getShopBaseInfo(Request $request)
    {
        $config = [
            'app_id' => env('WECHAT_MINI_PROGRAM_APPID_SHOP'),
            'secret' => env('WECHAT_MINI_PROGRAM_SECRET_SHOP'),

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => env('WECHAT_LOG_LEVEL', 'debug'),
                'file' => env('WECHAT_LOG_FILE', storage_path('logs/wechat-' . date('Y-m-d') . '.log')),
            ],
        ];
        $app = Factory::miniProgram($config);

        $code = $request->input('code');
        $result = $app->auth->session($code);
        Log::info('商家小程序获取的信息', ['code' => $code, 'result' => $request]);
        return $result;

    }

    /**
     * 获取二维码
     * @param Request $request
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function getUnlimitedQrcode(Request $request)
    {
        $id = $request->input('id');
        $inviteCode = $request->input('inviteCode');
        $response = $this->app->app_code->getUnlimit($id . '&' . $inviteCode, [
            'page' => 'pages/goods/detail',
            'width' => 600,
        ]);
        $url = '';
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $name = uniqid() . date('YmdHis');
            $filename = $response->saveAs('/tmp/temp/', $name . '.png');

            $pathName = 'feedback' . '/' . $filename;
            OSS::publicUpload('lepindian', $pathName, '/tmp/temp/' . $filename, ['ContentType' => 'image/png']);

            $url = OSS::getPublicObjectURL('lepindian', $pathName);

            $url = str_replace("http://lepindian.oss-cn-hangzhou.aliyuncs.com/", env('APP_ADMIN_URL'), $url);
        }
        return $url;
    }


}
