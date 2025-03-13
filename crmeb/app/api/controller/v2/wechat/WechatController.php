<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
namespace app\api\controller\v2\wechat;

use app\Request;
use app\services\wechat\WechatServices;
use crmeb\services\CacheService;
use crmeb\services\AccessToken;

/**
 * Class WechatController
 * @package app\api\controller\v2\wechat
 */
class WechatController
{
    protected $services = NUll;

    /**
     * WechatController constructor.
     * @param WechatServices $services
     */
    public function __construct(WechatServices $services)
    {
        $this->services = $services;
    }

    /**
     * 公众号授权登录，返回token
     * @param $spread
     * @return \think\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: 吴汐
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authLogin($spread = '')
    {
        $data = $this->services->authLogin($spread);
        return app('json')->success($data);
    }

    /**
     * 公众号授权绑定手机号
     * @param string $key
     * @param string $phone
     * @param string $captcha
     * @return \think\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: 吴汐
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authBindingPhone($key = '', $phone = '', $captcha = '')
    {
        //验证验证码
        $verifyCode = CacheService::get('code_' . $phone);
        if (!$verifyCode)
            return app('json')->fail(410009);
        $verifyCode = substr($verifyCode, 0, 6);
        if ($verifyCode != $captcha) {
            CacheService::delete('code_' . $phone);
            return app('json')->fail(410010);
        }
        CacheService::delete('code_' . $phone);
        $data = $this->services->authBindingPhone($key, $phone);
        return app('json')->success($data);
    }

    /**
     * 获取小程序scheme码
     * @param Request $request
     * @return mixed
     */
    public function generateScheme(Request $request)
    {
        $data = $request->postMore([
            ['path', ''],           // 通过scheme码进入的小程序页面路径
            ['query', ''],          // 通过scheme码进入小程序时的query
            ['expire_time', 0],     // 到期失效的scheme码的失效时间，为0则永久有效
            ['is_expire', false],   // 到期失效的scheme码失效类型，false为失效时间不变，true为随机失效时间
        ]);

        try {
            /** @var AccessToken $service */
            $service = app()->make(AccessToken::class);
            
            $params = [
                'jump_wxa' => [
                    'path' => $data['path'],
                    'query' => $data['query']
                ],
                'expire_time' => intval($data['expire_time']),
                'is_expire' => $data['is_expire']
            ];

            $result = $service->generateScheme($params);
            
            if (isset($result['errcode']) && $result['errcode'] != 0) {
                return app('json')->fail($result['errmsg'] ?? '生成scheme码失败');
            }

            return app('json')->success(['scheme' => $result['openlink'] ?? '']);
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage());
        }
    }
}
