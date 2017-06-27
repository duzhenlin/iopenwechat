<?php
namespace IopenWechat\Core\ServiceProviders;
use IopenWechat\User\User;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class UserServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['user'] = function() use ($pimple){
            return new User($pimple['auth']);
        };
    }

}