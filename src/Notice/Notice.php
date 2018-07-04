<?php
/**
 * Created by PhpStorm.
 * User: duzhenlin
 * Date: 2017/7/24
 * Time: 23:17
 */

namespace IopenWechat\Notice;

use IopenWechat\Core\AbstractAPI;
use IopenWechat\Core\Exceptions\InvalidArgumentException;

/**
 * Class Notice
 * @property  \IopenWechat\Auth\Auth $Auth
 * @package IopenWechat\Notice
 */
class Notice extends AbstractAPI
{
    /**
     * Default color.
     *
     * @var string
     */
    protected $defaultColor = '#173177';
    /**
     * Attributes.
     *
     * @var array
     */
    protected $message = [
        'touser' => '',
        'template_id' => '',
        'url' => '',
        'data' => [],
    ];
    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = ['touser', 'template_id'];
    /**
     * Message backup.
     *
     * @var array
     */
    protected $messageBackup;
    protected $Auth;
    const API_SEND_NOTICE = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
    const API_SET_INDUSTRY = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=';
    const API_ADD_TEMPLATE = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=';
    const API_GET_INDUSTRY = 'https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=';
    const API_GET_ALL_PRIVATE_TEMPLATE = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=';
    const API_DEL_PRIVATE_TEMPLATE = 'https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=';


    /**
     * Notice constructor.
     * @param $auth
     */
    public function __construct($auth)
    {

        $this->Auth = $auth;
        $this->messageBackup = $this->message;

    }

    /**
     * Set default color.
     *
     * @param string $color example: #0f0f0f
     *
     * @return $this
     */
    public function defaultColor($color)
    {
        $this->defaultColor = $color;
        return $this;
    }


    /**
     * @param $appId
     * @param $industryOne
     * @param $industryTwo
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function setIndustry($appId, $industryOne, $industryTwo)
    {
        $access_token = $this->getAuthorizerToken($appId);
        $params = [
            'industry_id1' => $industryOne,
            'industry_id2' => $industryTwo,
        ];
        return $this->parseJSON('json', [self::API_SET_INDUSTRY.$access_token, $params]);
    }


    /**
     * @param $appId
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function getIndustry($appId)
    {
        $access_token = $this->getAuthorizerToken($appId);
        return $this->parseJSON('json', [self::API_GET_INDUSTRY.$access_token]);
    }


    /**
     *  Add a template and get template ID.
     * @param $appId
     * @param $shortId
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function addTemplate($appId, $shortId)
    {
        $access_token = $this->getAuthorizerToken($appId);
        $params = ['template_id_short' => $shortId];
        return $this->parseJSON('json', [self::API_ADD_TEMPLATE.$access_token, $params]);
    }


    /**
     * 获取模板
     * @param $appId
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function getPrivateTemplates($appId)
    {
        $access_token = $this->getAuthorizerToken($appId);
        return $this->parseJSON('json', [self::API_GET_ALL_PRIVATE_TEMPLATE . $access_token]);
    }


    /**
     * 删除模板
     * @param $appId
     * @param $templateId
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function deletePrivateTemplate($appId, $templateId)
    {
        $access_token = $this->getAuthorizerToken($appId);
        $params = ['template_id' => $templateId];
        return $this->parseJSON('json', [self::API_DEL_PRIVATE_TEMPLATE . $access_token, $params]);
    }


    /**
     * 发送模板消息
     * @param $appId
     * @param array $data
     * @return \IopenWechat\Core\Collection
     * @throws InvalidArgumentException
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function send($appId, $data = [])
    {
        $access_token = $this->getAuthorizerToken($appId);
        $params = array_merge($this->message, $data);
        foreach ($params as $key => $value) {
            if (in_array($key, $this->required, true) && empty($value) && empty($this->message[$key])) {
                throw new InvalidArgumentException("Attribute '$key' can not be empty!");
            }
            $params[$key] = empty($value) ? $this->message[$key] : $value;
        }
        $params['data'] = $this->formatData($params['data']);
        $this->message = $this->messageBackup;
        return $this->parseJSON('json', [static::API_SEND_NOTICE . $access_token, $params]);
    }

    /**
     * Magic access..
     *
     * @param string $method
     * @param array $args
     *
     * @return Notice
     */
    public function __call($method, $args)
    {
        $map = [
            'template' => 'template_id',
            'templateId' => 'template_id',
            'uses' => 'template_id',
            'to' => 'touser',
            'receiver' => 'touser',
            'url' => 'url',
            'link' => 'url',
            'data' => 'data',
            'with' => 'data',
            'formId' => 'form_id',
            'prepayId' => 'form_id',
        ];
        if (0 === stripos($method, 'with') && strlen($method) > 4) {
            $method = lcfirst(substr($method, 4));
        }
        if (0 === stripos($method, 'and')) {
            $method = lcfirst(substr($method, 3));
        }
        if (isset($map[$method])) {
            $this->message[$map[$method]] = array_shift($args);
        }
        return $this;
    }

    /**
     * Format template data.
     *
     * @param array $data
     *
     * @return array
     */
    protected function formatData($data)
    {
        $return = [];
        foreach ($data as $key => $item) {
            if (is_scalar($item)) {
                $value = $item;
                $color = $this->defaultColor;
            } elseif (is_array($item) && !empty($item)) {
                if (isset($item['value'])) {
                    $value = strval($item['value']);
                    $color = empty($item['color']) ? $this->defaultColor : strval($item['color']);
                } elseif (count($item) < 2) {
                    $value = array_shift($item);
                    $color = $this->defaultColor;
                } else {
                    list($value, $color) = $item;
                }
            } else {
                $value = 'error data item.';
                $color = $this->defaultColor;
            }
            $return[$key] = [
                'value' => $value,
                'color' => $color,
            ];
        }
        return $return;
    }

    /**
     * @param $appid
     * @return mixed
     */
    private function getAuthorizerToken($appid)
    {
        $access_token = $this->Auth->getAuthorizerToken($appid);

        return $access_token;
    }

}
