<?php
/**
 * Created by PhpStorm.
 * User: duzhenlin
 * Date: 2018/6/29
 * Time: 9:58
 */

namespace IopenWechat\Mass;

use IopenWechat\Core\AbstractAPI;
use IopenWechat\Server\AccessToken;

/**
 * Class Mass
 * @property  \IopenWechat\Auth\Auth $Auth
 * @package IopenWechat\Mass
 */
class Mass extends AbstractAPI
{

    /**
     *根据标签进行群发【订阅号与服务号认证后均可用】
     */
    const SEND_MASS_ALL_URL = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?';
    /**
     *根据OpenID列表群发【订阅号不可用，服务号认证后可用】
     */
    const SEND_MASS_URL = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?';
    /**
     *删除群发【订阅号与服务号认证后均可用】
     */
    const DELETE_MASS_URL = 'https://api.weixin.qq.com/cgi-bin/message/mass/delete?';
    /**
     *预览接口【订阅号与服务号认证后均可用】
     */
    const PREVIEW_MASS_URL = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?';
    /**
     *上传图文消息素材【订阅号与服务号认证后均可用】
     */
    const UPLOAD_NEWS_URL = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=';
    /**
     *查询群发消息发送状态【订阅号与服务号认证后均可用】
     */
    const GET_MASS_URL = 'https://api.weixin.qq.com/cgi-bin/message/mass/get?';
    /**
     *获取群发速度
     */
    const GET_MASS_SPEED_URL = 'https://api.weixin.qq.com/cgi-bin/message/mass/speed/get?';
    /**
     *设置群发速度
     */
    const SET_MASS_SPEED_URL = 'https://api.weixin.qq.com/cgi-bin/message/mass/speed/set?';

    private $appid;

    private $Auth;
    private $access_token;

    /**
     * Member constructor.
     * @param  $auth
     */
    public function __construct($auth)
    {
        $this->Auth = $auth;

    }

    /**
     * @param $appId
     * @return mixed
     */
    private function getAuthorizerToken($appId)
    {
        $access_token = $this->Auth->getAuthorizerToken($appId);

        return $access_token;
    }


    /**
     * @param $appId
     * @param $articles
     * @return \IopenWechat\Core\Collection
     */
    public function uploadNews($appId, $articles)
    {
        $access_token = $this->getAuthorizerToken($appId);
        $params = ['articles' => $articles];
        return $this->parseJSON('json', [self::UPLOAD_NEWS_URL . $access_token, $params]);
    }

    public function sendAll($type)
    {

    }

    public function sendNewsByTags($appId, $articles, $isToAll = false)
    {
        $access_token = $this->getAuthorizerToken($appId);
        $params = ['articles' => $articles];
        return $this->parseJSON('json', [self::UPLOAD_NEWS_URL . $access_token, $params]);
    }
}