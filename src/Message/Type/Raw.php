<?php


/**
 * Raw.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class Raw.
 */
class Raw extends AbstractMessage
{
    /**
     * @var string
     */
    protected $msgType = 'raw';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = ['content'];

    /**
     * Constructor.
     *
     * @param array $content
     */
    public function __construct($content)
    {
        parent::__construct(['content' => strval($content)]);
    }
}
