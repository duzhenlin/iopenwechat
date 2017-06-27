<?php


/**
 * Link.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class Link.
 */
class Link extends AbstractMessage
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $msgType = 'link';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [
                             'title',
                             'description',
                             'url',
                            ];
}
