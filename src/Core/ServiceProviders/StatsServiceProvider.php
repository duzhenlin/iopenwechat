<?php
/**
 * Created by PhpStorm.
 * User: duzhenlin
 * Date: 2017/6/27
 * Time: 16:04
 *
 * @author      duzhenlin <duzhenlin@vip.qq.com>
 */

namespace IopenWechat\Core\ServiceProviders;

use IopenWechat\Stats\Stats;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class StatsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['stats'] = function () use ($pimple) {
            return new Stats($pimple['auth']);
        };
    }
}