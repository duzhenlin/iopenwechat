<?php
/**
 * Created by PhpStorm.
 * User: duzhenlin
 * Date: 2017/7/24
 * Time: 23:15
 */

namespace IopenWechat\Core\ServiceProviders;
use IopenWechat\Notice\Notice;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class NoticeServiceProvider  implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['notice'] = function ($pimple) {
            return new Notice($pimple['auth']);
        };
    }
}