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
        // var_dump($appid);
        $messageBuilder = new MessageBuilder($this);

        return $messageBuilder->message($message);
    }

    public function send($message)
    {
        // trace($message);

        $params = [
            'access_token' => $this->auth->getAuthorizerToken($this->appid),
        ];
        // trace($params);

        return $this->parseJSON('json', [self::SEND_MESSAGE_URL . http_build_query($params), $message]);
    }
}
