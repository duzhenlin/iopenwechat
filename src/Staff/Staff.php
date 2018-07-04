<?php

namespace IopenWechat\Staff;

use IopenWechat\Core\AbstractAPI;

class Staff extends AbstractAPI
{
    const SEND_MESSAGE_URL = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?';
    protected $auth;
    protected $appid;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param  $appid
     * @param  $message
     * @return MessageBuilder
     */
    public function message($appid, $message)
    {
        $this->appid = $appid;
        $messageBuilder = new MessageBuilder($this);

        return $messageBuilder->message($message);
    }


    /**
     * @param $message
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function send($message)
    {
        $params = [
            'access_token' => $this->auth->getAuthorizerToken($this->appid),
        ];

        return $this->parseJSON('json', [self::SEND_MESSAGE_URL . http_build_query($params), $message]);
    }
}
