<?php


/**
 * Music.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class Music.
 *
 * @property string $url
 * @property string $hq_url
 * @property string $title
 * @property string $description
 * @property string $thumb_media_id
 * @property string $format
 */
class Music extends AbstractMessage
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $msgType = 'music';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [
                             'title',
                             'description',
                             'url',
                             'hq_url',
                             'thumb_media_id',
                             'format',
                            ];
}
