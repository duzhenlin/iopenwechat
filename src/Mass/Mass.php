<?php
/**
 * Created by PhpStorm.
 * User: duzhenlin
 * Date: 2018/6/29
 * Time: 9:58
 */

namespace IopenWechat\Mass;

use IopenWechat\Core\AbstractAPI;

/**
 * 群发处理类
 * @property  \IopenWechat\Auth\Auth $Auth
 * @package IopenWechat\Mass
 */
class Mass extends AbstractAPI
{

    /**
     *根据标签进行群发【订阅号与服务号认证后均可用】
     */
    const SEND_MASS_ALL_URL = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=';
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


    /**
     * @var
     */
    private $Auth;

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
     * 上传图文
     * @param $appId
     * @param $articles
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function uploadNews($appId, $articles)
    {
        $access_token = $this->getAuthorizerToken($appId);
        $params = ['articles' => $articles];
        return $this->parseJSON('json', [self::UPLOAD_NEWS_URL . $access_token, $params]);
    }


    /**
     * 发送处理
     * @param $appId
     * @param $data
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    private function sendAll($appId, $data)
    {
        $access_token = $this->getAuthorizerToken($appId);

        return $this->parseJSON('json', [self::SEND_MASS_ALL_URL . $access_token, $data]);
    }


    /**
     * 发送图文
     *
     *  $data = [
     *      'filter' => [
     *         'is_to_all' => false,
     *          'tag_id' => 102,
     *       ],
     *       'media_id' => 'o4bS9MJ8MBEZP_HIGXn3IZaG_ageGD3tmnlt-hKRsWHtGOt3K-14dU_EIkGlNHkQ',
     *       'send_ignore_reprint' => '1'
     *   ];
     * @param $appId
     * @param $data
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function sendNewsAll($appId, $data)
    {
        $params['filter'] = $data['filter'];
        $params['mpnews']['media_id'] = $data['media_id'];
        $params['msgtype'] = 'mpnews';
        $params['send_ignore_reprint'] = $data['send_ignore_reprint'] !== '' ? $data['send_ignore_reprint'] : 0;
        return $this->sendAll($appId, $params);
    }


    /**
     * 发送文本群发
     * $data = [
     *      'filter' => [
     *         'is_to_all' => false,
     *          'tag_id' => 102,
     *       ],
     *       'content' => 'one day',
     *  ];
     * @param $appId
     * @param $data
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function sendTextAll($appId, $data)
    {
        $params['filter'] = $data['filter'];
        $params['text']['content'] = $data['content'];
        $params['msgtype'] = 'text';
        return $this->sendAll($appId, $params);
    }


    /**
     * 发送音频
     * @param $appId
     * @param $data
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function sendVoiceAll($appId, $data)
    {
        $params['filter'] = $data['filter'];
        $params['voice']['media_id'] = $data['media_id'];
        $params['msgtype'] = 'voice';
        return $this->sendAll($appId, $params);
    }


    /**
     * 发送图片
     * @param $appId
     * @param $data
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function sendImageAll($appId, $data)
    {
        $params['filter'] = $data['filter'];
        $params['image']['media_id'] = $data['media_id'];
        $params['msgtype'] = 'image';
        return $this->sendAll($appId, $params);
    }


    /**
     * 发送视频
     * @param $appId
     * @param $data
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function sendVideoAll($appId, $data)
    {
        $params['filter'] = $data['filter'];
        $params['mpvideo']['media_id'] = $data['media_id'];
        $params['msgtype'] = 'mpvideo';
        return $this->sendAll($appId, $params);
    }


    /**
     *  发送卡券
     * @param $appId
     * @param $data
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function sendWxCardAll($appId, $data)
    {
        $params['filter'] = $data['filter'];
        $params['wxcard']['card_id'] = $data['media_id'];
        $params['msgtype'] = 'wxcard';
        return $this->sendAll($appId, $params);
    }
}