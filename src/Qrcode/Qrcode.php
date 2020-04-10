<?php
/**
 * Created by PhpStorm
 * Author: duzhenlin
 * email: duzhenlin@vip.qq.com
 * Date: 2020/4/10
 * Time: 8:12
 */


namespace IopenWechat\Qrcode;

use IopenWechat\Core\AbstractAPI;
use IopenWechat\Core\App;
use Pimple\Container;

/**
 * Class Qrcode
 * @package IopenWechat\Qrcode
 */
class Qrcode extends AbstractAPI
{
    /**
     *  获取Ticket地址
     */
    const QRCODE_URL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?';
    /**
     *
     */
    const IMG_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';
    /**
     * 临时的整型参数值
     */
    const QR_SCENE = "QR_SCENE";
    /**
     *  临时的字符串参数值
     */
    const QR_STR_SCENE = "QR_STR_SCENE";
    /**
     * 永久的整型参数值
     */
    const QR_LIMIT_SCENE = "QR_LIMIT_SCENE";
    /**
     * 永久的字符串参数值
     */
    const QR_LIMIT_STR_SCENE = "QR_LIMIT_STR_SCENE";
    /**
     * @var array
     */
    private $int_type = [
        self::QR_SCENE,
        self::QR_LIMIT_SCENE,
    ];
    /**
     * @var array
     */
    private $string_type = [
        self::QR_STR_SCENE,
        self::QR_LIMIT_STR_SCENE,
    ];
    /**
     * @var App
     */
    protected $container;

    /**
     * Qrcode constructor.
     * @param Container $pimple
     */
    public function __construct(Container $pimple)
    {
        $this->container = $pimple;
    }


    /**
     * @param     $appid
     * @param     $action_name
     * @param     $data
     * @param int $expire_seconds
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function getTicket($appid, $action_name, $data, $expire_seconds = 1800)
    {
        $access_token = $this->container->auth->getAuthorizerToken($appid);
        $params       = [
            'expire_seconds' => $expire_seconds,
            'action_name'    => $action_name,
            'action_info'    => [
                'scene' => []
            ]
        ];
        if (in_array($access_token, $this->int_type)) {
            $params['action_info']['scene']['scene_id'] = $data;
        }
        if (in_array($access_token, $this->string_type)) {
            $params['action_info']['scene']['scene_str'] = $data;
        }
        return $this->parseJSON('json', [self::QRCODE_URL . '?access_token=' . $access_token, $params]);
    }

    /**
     * @param $ticket
     * @return string
     */
    public function getImg($ticket)
    {
        return self::IMG_URL . urlencode($ticket);
    }
}
