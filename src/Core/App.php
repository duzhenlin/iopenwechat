<?php

namespace IopenWechat\Core;

use Doctrine\Common\Cache\Cache as CacheInterface;
use Doctrine\Common\Cache\FilesystemCache;
use IopenWechat\Core\Helper\XmlHelper;
use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class App
 * @property  \IopenWechat\Auth\Auth                    $auth                    授权部分接口
 * @property  \IopenWechat\Authorizer\Member            $member                  用户部分接口
 * @property  \IopenWechat\Auth\AccessToken             $authorizer_access_token 授权部分接口
 * @property  \IopenWechat\Js\API                       $jsapi                   JSSDK
 * @property  \IopenWechat\Material\Material            $material                素材管理
 * @property  \IopenWechat\Material\Temporary           $material_temporary      临时素材管理
 * @property  \IopenWechat\Menu\Menu                    $menu                    菜单管理
 * @property  \IopenWechat\Message\Message              $message                 消息管理
 * @property  \IopenWechat\Notice\notice                $notice                  通知管理
 * @property  \IopenWechat\Oauth\Oauth                  $oauth
 * @property  \IopenWechat\Oauth\AccessToken            $oauth_access_token
 * @property  \IopenWechat\Quota\Quota                  $quota
 * @property  \IopenWechat\Server\AccessToken           $access_token
 * @property  \IopenWechat\Server\Wxcrypt               $wxcrypt
 * @property  \IopenWechat\Server\PreAuthCode           $autocode
 * @property  \IopenWechat\Server\EventNotice           $event
 * @property  \IopenWechat\Staff\Staff                  $staff
 * @property  \IopenWechat\Stats\Stats                  $stats
 * @property  \IopenWechat\User\User                    $user
 * @property  \IopenWechat\Mass\Mass                    $mass
 * @property  CacheInterface                            $cache
 *
 * @property  \IopenWechat\Core\Helper\XmlHelper        $xml
 * @property  \Symfony\Component\HttpFoundation\Request $request
 * @package IopenWechat\Core
 */
class App extends Container
{
    /**
     * 初始化参数允许的数组键
     *
     */
    protected static $valid_config_key = [
        'appid',
        'token',
        'encodingAesKey',
        'cache_dir',
        'appsecret',
        'cache'
    ];
    /**
     * Service Providers.
     *
     * @var array
     */
    protected $providers = [
        ServiceProviders\ServerServiceProvider::class,
        ServiceProviders\AuthServiceProvider::class,
        ServiceProviders\AuthorizerServiceProvider::class,
        ServiceProviders\QuotaServiceProvider::class,
        ServiceProviders\OauthServiceProvider::class,
        ServiceProviders\MessageServiceProvider::class,
        ServiceProviders\UserServiceProvider::class,
        ServiceProviders\JsApiServiceProvider::class,
        ServiceProviders\StatsServiceProvider::class,
        ServiceProviders\StaffServiceProvider::class,
        ServiceProviders\MaterialServiceProvider::class,
        ServiceProviders\MenuServiceProvider::class,
        ServiceProviders\NoticeServiceProvider::class,
        ServiceProviders\MassServiceProvider::class,
    ];

    /**
     * Application constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        parent::__construct();

        $config              = $this->filterConfig($config);
        $config['ticketKey'] = 'sinre.openwechat.verifyticket.';
        $this['config']      = function () use ($config) {
            return new Config($config);
        };

        if ($this['config']['debug']) {
            error_reporting(E_ALL);
        }

        $this->registerProviders();
        $this->registerBase();

        Http::setDefaultOptions($this['config']->get('guzzle', ['timeout' => 5.0]));
    }

    protected function filterConfig($config)
    {
        foreach ($config as $key => $val) {
            if (!in_array($key, self::$valid_config_key)) {
                unset($config[$key]);
            }
        }
        return $config;
    }

    protected function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    protected function registerBase()
    {
        $this['request'] = function () {
            return Request::createFromGlobals();
        };

        if (!empty($this['config']['cache']) && $this['config']['cache'] instanceof CacheInterface) {
            $this['cache'] = $this['config']['cache'];
        } else {
            $this['cache'] = function () {
                return new FilesystemCache($this['config']->get('cache_dir', sys_get_temp_dir()));
            };
        }

        $this['xml'] = function () {
            return new XmlHelper();
        };

    }

    public function addProvider($provider)
    {
        array_push($this->providers, $provider);

        return $this;
    }

    public function setProviders(array $providers)
    {
        $this->providers = [];

        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    public function getProviders()
    {
        return $this->providers;
    }

    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }
}
