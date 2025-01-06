<?php
/**
 * Created by Duzhenlin
 * Author: Duzhenlin
 * email: duzhenlin@vip.qq.com
 * Date: 2025/1/6
 * Time: 11:54
 */

namespace IopenWechat\Core\ServiceProviders;


use IopenWechat\Comments\Comments;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CommentsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['comments'] = function () use ($pimple) {
            return new Comments($pimple);
        };
    }

}
