<?php


/**
 * News.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class News.
 */
class News extends AbstractMessage
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $msgType = 'news';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [
                                'title',
                                'description',
                                'url',
                                'picurl',
                            ];
    /**
     * Aliases of attribute.
     *
     * @var array
     */
    protected $aliases = [
        'image' => 'pic_url',
    ];
}
