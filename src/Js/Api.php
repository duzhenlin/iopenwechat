<?php
namespace IopenWechat\Js;

use Doctrine\Common\Cache\Cache;
use IopenWechat\Core\AbstractAPI;
use IopenWechat\Core\Helper\StringHelper;

/**
 * Class Api
 * @package IopenWechat\Js
 */
class Api extends AbstractAPI
{
    /**
     *
     */
    const JSAPI_TICKET = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?';
    /**
     * @var
     */
    protected $auth;
    /**
     * @var string
     */
    protected $cacheKey = 'sinre.IopenWechat.jsapi_ticket.';

    /**
     * Api constructor.
     * @param       $auth
     * @param Cache $cache
     */
    public function __construct($auth, Cache $cache)
    {
        $this->auth = $auth;
    }
    /**
     * Get config json for jsapi.
     *
     * @param  array          $APIs
     * @param  bool           $debug
     * @param  bool           $beta
     * @param  bool           $json
     * @return array|string
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function config($appid, $url, array $APIs, $debug = false, $beta = false, $json = true)
    {
        $signPackage = $this->getJsSign($appid, $url);

        $base = [
            'debug' => $debug,
            'beta'  => $beta,
        ];
        $config = array_merge($base, $signPackage, ['jsApiList' => $APIs]);

        return $json ? json_encode($config) : $config;
    }


    /**
     * @param $appid
     * @param bool $forceRefresh
     * @return false|mixed
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function getCacheJsTicket($appid, $forceRefresh = false)
    {
        $ticket = $this->getCacheHandler()->fetch($this->cacheKey . $appid);
        if (!$ticket || $forceRefresh) {
            $ticket = $this->getJsTicket($appid);
        }
        return $ticket;
    }


    /**
     * @param $appid
     * @return mixed
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    protected function getJsTicket($appid)
    {
        $params = [
            'access_token' => $this->auth->getAuthorizerToken($appid),
            'type'         => 'jsapi',
        ];
        $result = $this->parseJSON('get', [self::JSAPI_TICKET, $params]);

        $this->getCacheHandler()->save($this->cacheKey . $appid, $result['ticket'], $result['expires_in'] - 500);

        return $result['ticket'];
    }

    /**
     * @param $ticket
     * @param $nonce
     * @param $timestamp
     * @param $url
     * @return string
     */
    protected function signature($ticket, $nonce, $timestamp, $url)
    {
        return sha1("jsapi_ticket={$ticket}&noncestr={$nonce}&timestamp={$timestamp}&url={$url}");
    }

    /**
     * @param $url
     * @return bool|string
     */
    protected function fixUrl($url)
    {
        $position = strpos($url, '#');
        if ($position) {
            $url = substr($url, 0, $position);
        }
        return $url;
    }


    /**
     * @param $appid
     * @param $url
     * @param null $noncestr
     * @param null $timestamp
     * @return array
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function getJsSign($appid, $url, $noncestr = null, $timestamp = null)
    {
        $jsapi_ticket = $this->getCacheJsTicket($appid);
        $noncestr     = $noncestr ?: StringHelper::quickRandom(10);
        $timestamp    = $timestamp ?: time();
        $url          = $this->fixUrl($url);
        return [
            'appid'     => $appid,
            'nonceStr'  => $noncestr,
            'timestamp' => $timestamp,
            'url'       => $url,
            'signature' => $this->signature($jsapi_ticket, $noncestr, $timestamp, $url),
        ];
    }

}
