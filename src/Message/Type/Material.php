<?php


/**
 * Material.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class Material.
 */
class Material extends AbstractMessage
{
    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = ['media_id'];

    /**
     * Material constructor.
     *
     * @param string $mediaId
     * @param string $type
     */
    public function __construct($type, $mediaId)
    {
        $this->set('media_id', $mediaId);
        $this->msgType = $type;
    }
}
