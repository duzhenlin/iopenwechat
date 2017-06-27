<?php

namespace IopenWechat\Core\ServiceProviders;
use IopenWechat\Quota\Quota;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class QuotaServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['quota'] = function() use ($pimple){
            return new Quota($pimple);
        };
    }

}