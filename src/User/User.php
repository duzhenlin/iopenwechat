<?php

namespace IopenWechat\User;

use IopenWechat\Core\AbstractAPI;

/**
 * Class User
 * @package IopenWechat\User
 */
class User extends AbstractAPI
{
    /**
     *
     */
    const USER_INFO_URL = 'https://api.weixin.qq.com/cgi-bin/user/info';
    /**
     *
     */
    const USER_API_BATCH_GET = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=';
    /**
     *
     */
    const USER_API_LIST = 'https://api.weixin.qq.com/cgi-bin/user/get';
    /**
     *
     */
    const USER_API_GROUP = 'https://api.weixin.qq.com/cgi-bin/groups/getid';
    /**
     *
     */
    const USER_API_REMARK = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=';
    /**
     *
     */
    const USER_API_OAUTH_GET = 'https://api.weixin.qq.com/sns/userinfo';
    /**
     * @var
     */
    protected $auth;

    /**
     * User constructor.
     * @param $auth
     */
    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param        $appid
     * @param        $openid
     * @param string $lang
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function getInfo($appid, $openid, $lang = 'zh_CN')
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params = [
            'access_token' => $access_token,
            'openid' => $openid,
            'lang' => $lang,
        ];

        $userInfo = $this->parseJSON('get', [self::USER_INFO_URL, $params]);
        return $userInfo;
    }


    /**
     * @param        $appid
     * @param array  $openIds
     * @param string $lang
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function batchGet($appid, array $openIds, $lang = 'zh_CN')
    {
        $params = [];
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params['user_list'] = array_map(function ($openId) use ($lang) {
            return [
                'openid' => $openId,
                'lang' => $lang,
            ];
        }, $openIds);
        return $this->parseJSON('json', [self::USER_API_BATCH_GET . $access_token, $params]);
    }


    /**
     * @param      $appid
     * @param null $nextOpenId
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function lists($appid, $nextOpenId = null)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params = [
            'access_token' => $access_token,
            'next_openid' => $nextOpenId,
        ];

        return $this->parseJSON('get', [self::USER_API_LIST, $params]);
    }


    /**
     * @param $appid
     * @param $openId
     * @param $remark
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function remark($appid, $openId, $remark)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params = [
            'openid' => $openId,
            'remark' => $remark,
        ];

        return $this->parseJSON('json', [self::USER_API_REMARK . $access_token, $params]);
    }

}
