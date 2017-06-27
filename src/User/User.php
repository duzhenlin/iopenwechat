<?php
namespace IopenWechat\User;

use IopenWechat\Core\AbstractAPI;

class User extends AbstractAPI
{
    const USER_INFO_URL      = 'https://api.weixin.qq.com/cgi-bin/user/info';
    const USER_API_BATCH_GET = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=';
    const USER_API_LIST      = 'https://api.weixin.qq.com/cgi-bin/user/get';
    const USER_API_GROUP     = 'https://api.weixin.qq.com/cgi-bin/groups/getid';
    const USER_API_REMARK    = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=';
    const USER_API_OAUTH_GET = 'https://api.weixin.qq.com/sns/userinfo';
    protected $auth;
    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    public function getInfo($appid, $openid, $lang = 'zh_CN')
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params       = [
            'access_token' => $access_token,
            'openid'       => $openid,
            'lang'         => $lang,
        ];

        $userInfo = $this->parseJSON('get', [self::USER_INFO_URL, $params]);
        return $userInfo;
    }

    /**
     * Batch get users.
     *
     * @param  array                            $openIds
     * @param  string                           $lang
     * @return \EasyWeChat\Support\Collection
     */
    public function batchGet($appid, array $openIds, $lang = 'zh_CN')
    {
        $params              = [];
        $access_token        = $this->auth->getAuthorizerToken($appid);
        $params['user_list'] = array_map(function ($openId) use ($lang) {
            return [
                'openid' => $openId,
                'lang'   => $lang,
            ];
        }, $openIds);
        return $this->parseJSON('json', [self::USER_API_BATCH_GET . $access_token, $params]);
    }

    /**
     * List users.
     *
     * @param  string                           $nextOpenId
     * @return \EasyWeChat\Support\Collection
     */
    public function lists($appid, $nextOpenId = null)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params       = [
            'access_token' => $access_token,
            'next_openid'  => $nextOpenId,
        ];

        return $this->parseJSON('get', [self::USER_API_LIST, $params]);
    }

    /**
     * Set user remark.
     *
     * @param  string $openId
     * @param  string $remark
     * @return bool
     */
    public function remark($appid, $openId, $remark)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params       = [
            'openid' => $openId,
            'remark' => $remark,
        ];

        return $this->parseJSON('json', [self::USER_API_REMARK . $access_token, $params]);
    }

}
