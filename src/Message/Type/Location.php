<?php


/**
 * Location.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class Location.
 */
class Location extends AbstractMessage
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $msgType = 'location';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [
                             'latitude',
                             'longitude',
                             'scale',
                             'label',
                             'precision',
                            ];
}
