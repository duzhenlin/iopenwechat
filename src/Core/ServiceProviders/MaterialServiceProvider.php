<?php

namespace IopenWechat\Core\ServiceProviders;

use IopenWechat\Material\Material;
use IopenWechat\Material\Temporary;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class MaterialServiceProvider.
 */
class MaterialServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['material'] = function ($pimple) {
            return new Material($pimple['auth']);
        };

        $temporary = function ($pimple) {
            return new Temporary($pimple['auth']);
        };

        $pimple['material_temporary'] = $temporary;
        $pimple['material.temporary'] = $temporary;
    }
}
