<?php

namespace IopenWechat\Core\ServiceProviders;

use IopenWechat\Qrcode\Qrcode;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class QrcodeServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['qr_code'] = function () use ($pimple) {
            return new Qrcode($pimple);
        };
    }

}