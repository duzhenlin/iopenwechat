<?php


/**
 * Article.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace IopenWechat\Message\Type;

/**
 * Class Article.
 */
class Article extends AbstractMessage
{
    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [
                                'thumb_media_id',
                                'author',
                                'title',
                                'content',
                                'digest',
                                'source_url',
                                'show_cover',
                            ];

    /**
     * Aliases of attribute.
     *
     * @var array
     */
    protected $aliases = [
        'source_url' => 'content_source_url',
        'show_cover' => 'show_cover_pic',
    ];
}
