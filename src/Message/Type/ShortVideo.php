<?php


/**
 * ShortVideo.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class ShortVideo.
 *
 * @property string $title
 * @property string $media_id
 * @property string $description
 * @property string $thumb_media_id
 */
class ShortVideo extends Video
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $msgType = 'shortvideo';
}
