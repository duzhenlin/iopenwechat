<?php


/**
 * Image.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class Image.
 *
 * @property string $media_id
 */
class Image extends AbstractMessage
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $msgType = 'image';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = ['media_id'];

    /**
     * Set media_id.
     *
     * @param string $mediaId
     *
     * @return Image
     */
    public function media($mediaId)
    {
        $this->setAttribute('media_id', $mediaId);

        return $this;
    }
}
