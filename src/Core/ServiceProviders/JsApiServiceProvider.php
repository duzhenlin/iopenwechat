<?php

namespace IopenWechat\Core\ServiceProviders;

use IopenWechat\Js\Api;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class JsApiServiceProvider
 * @package IopenWechat\Core\ServiceProviders
 */
class JsApiServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['jsapi'] = function () use ($pimple) {
            return new Api($pimple['auth'], $pimple['cache']);
        };
    }

}