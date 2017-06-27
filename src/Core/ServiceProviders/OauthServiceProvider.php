<?php
namespace IopenWechat\Core\ServiceProviders;

use IopenWechat\Oauth\AccessToken;
use IopenWechat\Oauth\Oauth;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class OauthServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['oauth'] = function() use ($pimple){
            return new Oauth($pimple['config']['appid'], $pimple['access_token'], $pimple['oauth_access_token']);
        };

        $pimple['oauth_access_token'] = function() use ($pimple){
            return new AccessToken($pimple);
        };
    }

}