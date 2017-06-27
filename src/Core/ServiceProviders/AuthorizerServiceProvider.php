<?php

namespace IopenWechat\Core\ServiceProviders;

use IopenWechat\Authorizer\Member;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class AuthorizerServiceProvider
 * @package IopenWechat\Core\ServiceProviders
 */
class AuthorizerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['member'] = function() use ($pimple){
            return new Member($pimple['config']['appid'], $pimple['access_token']);
        };
    }

}