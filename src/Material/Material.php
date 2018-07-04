<?php

namespace IopenWechat\Material;

use IopenWechat\Core\AbstractAPI;
use IopenWechat\Core\Exceptions\InvalidArgumentException;
use IopenWechat\Message\Type\Article;


/**
 * Class Material
 * @property  \IopenWechat\Auth\Auth $auth
 * @package IopenWechat\Material
 */
class Material extends AbstractAPI
{
    /**
     * Allow media type.
     *
     * @var array
     */
    protected $allowTypes = ['image', 'voice', 'video', 'thumb', 'news_image'];

    const API_GET = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=';
    const API_MEDIA_GET = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=';
    const API_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/material/add_material';
    const API_DELETE = 'https://api.weixin.qq.com/cgi-bin/material/del_material';
    const API_STATS = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=';
    const API_LISTS = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=';
    const API_NEWS_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=';
    const API_NEWS_UPDATE = 'https://api.weixin.qq.com/cgi-bin/material/update_news?access_token=';
    const API_NEWS_IMAGE_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg';
    const API_NEWS_VIDEO_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/media/uploadvideo';
    protected $auth;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }


    /**
     * Upload image.
     * @param $path
     * @param $appId
     * @return string
     * @throws InvalidArgumentException
     */
    public function uploadImage($appId, $path)
    {
        return $this->uploadMedia($appId, 'image', $path);
    }


    /**
     * Upload voice.
     * @param $appId
     * @param $path
     * @return string
     * @throws InvalidArgumentException
     */
    public function uploadVoice($appId, $path)
    {
        return $this->uploadMedia($appId, 'voice', $path);
    }


    /**
     * Upload thumb.
     * @param $appId
     * @param $path
     * @return string
     * @throws InvalidArgumentException
     */
    public function uploadThumb($appId, $path)
    {
        return $this->uploadMedia($appId, 'thumb', $path);
    }


    /**
     * @param $path
     * @param $appId
     * @param $title
     * @param $description
     * @return string
     * @throws InvalidArgumentException
     */
    public function uploadVideo($appId, $path, $title, $description)
    {
        $params = [
            'description' => json_encode(
                [
                    'title' => $title,
                    'introduction' => $description,
                ], JSON_UNESCAPED_UNICODE),
        ];

        return $this->uploadMedia($appId, 'video', $path, $params);
    }

    /**
     * Upload articles.
     *
     * @param  array|Article $articles
     * @return string
     */
    public function uploadArticle($articles)
    {
        if (!empty($articles['title']) || $articles instanceof Article) {
            $articles = [$articles];
        }

        $params = [
            'articles' => array_map(function ($article) {
                if ($article instanceof Article) {
                    return $article->only([
                        'title',
                        'thumb_media_id',
                        'author',
                        'digest',
                        'show_cover_pic',
                        'content',
                        'content_source_url',
                    ]);
                }

                return $article;
            }, $articles)
        ];

        return $this->parseJSON('json', [self::API_NEWS_UPLOAD, $params]);
    }


    /**
     *  Update article.
     * @param     $mediaId
     * @param     $article
     * @param int $index
     * @return \IopenWechat\Core\Collection
     */
    public function updateArticle($mediaId, $article, $index = 0)
    {
        $params = [
            'media_id' => $mediaId,
            'index' => $index,
            'articles' => isset($article['title']) ? $article : (isset($article[$index]) ? $article[$index] : []),
        ];

        return $this->parseJSON('json', [self::API_NEWS_UPDATE, $params]);
    }


    /**
     * Upload image for article.
     * @param $appId
     * @param $path
     * @return string
     * @throws InvalidArgumentException
     */
    public function uploadArticleImage($appId, $path)
    {
        return $this->uploadMedia($appId, 'news_image', $path);
    }

    /**
     * @param $appId
     * @param $path
     * @return string
     * @throws InvalidArgumentException
     */
    public function uploadArticleVideo($appId, $path)
    {
        return $this->uploadMedia($appId, 'news_video', $path);
    }


    /**
     * Fetch material.
     * @param $appid
     * @param $mediaId
     * @return bool|mixed|\Psr\Http\Message\StreamInterface
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function get($appid, $mediaId)
    {
        $access_token = $this->getAuthorizerToken($appid);
        $response = $this->getHttp()->json(self::API_GET . $access_token, ['media_id' => $mediaId]);
        foreach ($response->getHeader('Content-Type') as $mime) {
            if (preg_match('/(image|video|audio)/i', $mime)) {
                return $response->getBody();
            }
        }

        $json = $this->getHttp()->parseJSON($response);

        // XXX: 微信开发这帮混蛋，尼玛文件二进制输出不带header，简直日了!!!
        if (!$json) {
            // $info[''] =
            return $response->getBody();
        }

        $this->checkAndThrow($json);

        return $json;
    }

    /**
     *  Delete material by media ID.
     * @param $mediaId
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function delete($mediaId)
    {
        return $this->parseJSON('json', [self::API_DELETE, ['media_id' => $mediaId]]);
    }

    /**
     * List materials.
     *
     * example:
     *
     * {
     *   "total_count": TOTAL_COUNT,
     *   "item_count": ITEM_COUNT,
     *   "item": [{
     *             "media_id": MEDIA_ID,
     *             "name": NAME,
     *             "update_time": UPDATE_TIME
     *         },
     *         // more...
     *   ]
     * }
     *
     * @param  string $type
     * @param  int $offset
     * @param  int $count
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     *
     */
    public function lists($appid, $type, $offset = 0, $count = 20)
    {
        $access_token = $this->getAuthorizerToken($appid);
        $params = [
            'type' => $type,
            'offset' => intval($offset),
            'count' => min(20, $count),
        ];

        return $this->parseJSON('json', [self::API_LISTS . $access_token, $params]);
    }

    /**
     * Get stats of materials.
     *
     * @return \IopenWechat\Core\Collection
     */
    public function stats($appid)
    {
        $access_token = $this->getAuthorizerToken($appid);
        return $this->parseJSON('get', [self::API_STATS, ['access_token' => $access_token]]);
    }

    /**
     * Upload material.
     *
     * @param  string $type
     * @param  string $path
     * @param  array $form
     * @throws InvalidArgumentException
     * @return string
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    protected function uploadMedia($appId, $type, $path, array $form = [])
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new InvalidArgumentException("File does not exist, or the file is unreadable: '$path'");
        }

        $form['type'] = $type;

        $queries['access_token'] = $this->getAuthorizerToken($appId);

        return $this->parseJSON('upload', [$this->getAPIByType($type, $appId), ['media' => $path], $form, $queries]);
    }

    /**
     * Get API by type.
     *
     * @param  string $type
     * @param  string $appId
     * @return string
     */
    public function getAPIByType($type, $appId)
    {

        switch ($type) {
            case 'news_image':
                $api = self::API_NEWS_IMAGE_UPLOAD;
                break;
            case 'news_video':
                $api = self::API_NEWS_VIDEO_UPLOAD;
                break;
            default:
                $api = self::API_UPLOAD;
        }

        return $api;
    }


    /**
     * 获取公众号token
     * @param $appid
     * @return mixed
     */
    private function getAuthorizerToken($appid)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);

        return $access_token;
    }
}
