<?php

namespace IopenWechat\Auth;

use Doctrine\Common\Cache\Cache;
use IopenWechat\Core\AbstractAPI;
use IopenWechat\Core\Exceptions\HttpException;

/**
 * Class AccessToken
 * @package OpenWechat\Auth
 */
class AccessToken extends AbstractAPI
{

    /**
     *
     * @var
     */
    protected $appid;
    /**
     * @var string
     */
    protected $accessTokenPrefix;
    /**
     * @var string
     */
    protected $refreshTokenPrefix;

    /**
     *
     */
    const AUTHORIZER_TOKEN_URL = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=';

    /**
     * AccessToken constructor.
     * @param       $appid
     * @param Cache $cache
     */
    public function __construct($appid, Cache $cache)
    {
        $this->appid              = $appid;
        $this->cache              = $cache;
        $this->accessTokenPrefix  = 'sinre.openwechat.authorizer_access_token.';
        $this->refreshTokenPrefix = 'sinre.openwechat.authorizer_refresh_token.';
    }

    /**
     * 缓存token
     * @param string      $authorizer_appid         授权方appid
     * @param string|null $authorizer_access_token  授权方令牌
     * @param int|null    $expire_in                授权方令牌的过期时间
     * @param string|null $authorizer_refresh_token 刷新令牌
     */
    public function cacheToken($authorizer_appid, $authorizer_access_token = null, $expire_in = null, $authorizer_refresh_token = null)
    {
        if ($authorizer_access_token) {
            $this->getCacheHandler()->save($this->accessTokenPrefix . $authorizer_appid, $authorizer_access_token, $expire_in - 1500);
        }

        // authorizer_refresh_token 是授权方长期刷新凭据，不能被 null/false/空字符串覆盖。
        // 一旦被空值污染，后续刷新 authorizer_access_token 会向微信发送非法参数并触发 47001。
        if (is_string($authorizer_refresh_token) && $authorizer_refresh_token !== '') {
            $this->getCacheHandler()->save($this->refreshTokenPrefix . $authorizer_appid, $authorizer_refresh_token, 0);
        }
    }

    /**
     * 获取授权公众号的接口调用凭据
     * @param  string             $authorizer_appid 授权方appid
     * @param  string             $access_token     第三方平台component_access_token
     * @param  bool               $forceRefresh     是否强制刷新token
     * @return false|mixed|null
     */
    public function getToken($authorizer_appid, $access_token, $forceRefresh = false)
    {
        $token = $this->getCacheHandler()->fetch($this->accessTokenPrefix . $authorizer_appid);
        if (!$token || $forceRefresh) {
            $token = $this->getAccessToken($authorizer_appid, $access_token);
        }
        return $token;
    }

    /**
     * 获取授权公众号的接口调用凭据
     * @param  string       $authorizer_appid 授权方appid
     * @param  string       $access_token     第三方平台component_access_token
     * @return mixed|null
     */
    protected function getAccessToken($authorizer_appid, $access_token)
    {
        $refreshToken = $this->getCacheHandler()->fetch($this->refreshTokenPrefix . $authorizer_appid);
        if (!is_string($refreshToken) || $refreshToken === '') {
            // 缺少 authorizer_refresh_token 时重试无法恢复，只能重新授权或从备份恢复刷新令牌。
            // 这里提前拦截，避免把 false 发给微信导致误判为统计接口参数 47001。
            throw new HttpException('授权方刷新令牌缺失，请重新授权公众号：' . $authorizer_appid);
        }

        $params = [
            'component_appid'          => $this->appid,
            'authorizer_appid'         => $authorizer_appid,
            'authorizer_refresh_token' => $refreshToken,
        ];

        $token = $this->parseJSON('post', [self::AUTHORIZER_TOKEN_URL . $access_token, json_encode($params)]);

        $this->cacheToken($authorizer_appid, $token['authorizer_access_token'], $token['expires_in'], $token['authorizer_refresh_token']);

        return $token['authorizer_access_token'];
    }
}
