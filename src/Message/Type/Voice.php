<?php


/**
 * Voice.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class Voice.
 *
 * @property string $media_id
 */
class Voice extends AbstractMessage
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $msgType = 'voice';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = ['media_id'];

    /**
     * Set media id.
     *
     * @param string $mediaId
     *
     * @return Voice
     */
    public function media($mediaId)
    {
        $this->setAttribute('media_id', $mediaId);

        return $this;
    }
}
