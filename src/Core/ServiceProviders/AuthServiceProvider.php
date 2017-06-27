<?php
namespace IopenWechat\Core\ServiceProviders;

use IopenWechat\Auth\AccessToken;
use IopenWechat\Auth\Auth;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class AuthServiceProvider
 * @package IopenWechat\Core\ServiceProviders
 */
class AuthServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['auth'] = function() use ($pimple){
            return new Auth($pimple);
        };

        $pimple['authorizer_access_token'] = function() use ($pimple){
            return new AccessToken($pimple['config']['appid'], $pimple['cache']);
        };
    }

}