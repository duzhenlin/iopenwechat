<?php

namespace IopenWechat\Core\ServiceProviders;



use IopenWechat\Mass\Mass;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class AuthorizerServiceProvider
 * @package IopenWechat\Core\ServiceProviders
 */
class MassServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['mass'] = function () use ($pimple) {
            return new Mass($pimple['auth']);
        };
    }

}