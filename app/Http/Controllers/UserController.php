<?php
/**
 * 用户信息
 */

namespace App\Http\Controllers;


use App\ConstDir\BaseConst;
use App\Http\Controllers\Controller;
use App\Logic\UserLogic;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 保存用户信息
     * @param Request $request
     * @return bool
     */
    public function saveUser(Request $request)
    {
        $data['openid'] = $request->input('openid');
        $data['nickname'] = $request->input('nickName', '');
        $data['avatar'] = $request->input('avatarUrl', '');
        $data['country'] = $request->input('country', '');
        $data['gender'] = $request->input('gender', 0);
        $data['province'] = $request->input('province', '');
        $data['city'] = $request->input('city', '');
        $data['realname'] = $request->input('realname', '');
        $data['mobile_phone'] = $request->input('mobile_phone', '');
        foreach ($data as $key => $value) {
            if (empty($value) && $value !== 0) {
                unset($data[$key]);
            }
        }
        return UserLogic::getInstance()->saveUser($data);
    }

    /**
     * 获取用户信息
     * @param Request $request
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function getInfo(Request $request)
    {
        $userId = $request->input('userId');
        return UserLogic::getInstance()->getUserInfo($userId);
    }


    /**
     * 获取代理信息
     * @param Request $request
     * @return array|mixed
     */
    public function getChildUser(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('pageSize', BaseConst::PAGE_NUM);
        $userId = $request->input('userId');
        return UserLogic::getInstance()->getAgentUser($userId, $page, $pageSize);
    }

    /**
     * 获取分享金额
     * @param Request $request
     * @return array
     */
    public function getShareMoney(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('pageSize', BaseConst::PAGE_NUM);
        $userId = $request->input('userId');

        return UserLogic::getInstance()->getShareMoney($userId, $page, $pageSize);
    }

    /**
     * 获取折扣
     * @param Request $request
     * @return float|int
     * @throws \App\Exceptions\ApiException
     */
    public function getAccount(Request $request)
    {
        $code = $request->input('code');
        return UserLogic::getInstance()->getBail($code);

    }
}
