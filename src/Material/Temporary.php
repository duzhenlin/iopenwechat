<?php

namespace IopenWechat\Material;

use IopenWechat\Core\AbstractAPI;
use IopenWechat\Core\Exceptions\InvalidArgumentException;
use IopenWechat\Core\Helper\File;


/**
 * Class Temporary
 * @package IopenWechat\Material
 */
class Temporary extends AbstractAPI
{
    /**
     * Allow media type.
     *
     * @var array
     */
    protected $allowTypes = ['image', 'voice', 'video', 'thumb'];

    const API_GET = 'https://api.weixin.qq.com/cgi-bin/media/get?';
    const API_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/media/upload?';
    protected $auth;

    /**
     * Temporary constructor.
     * @param $auth
     */
    public function __construct($auth)
    {
        $this->auth = $auth;
    }


    /**
     * 下载临时素材
     * Download temporary material.
     * @param        $appId
     * @param        $mediaId
     * @param        $directory
     * @param string $filename
     * @return string
     * @throws InvalidArgumentException
     */
    public function download($appId, $mediaId, $directory, $filename = '')
    {
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new InvalidArgumentException("Directory does not exist or is not writable: '$directory'.");
        }

        $filename = $filename ?: $mediaId;

        $stream = $this->getStream($appId, $mediaId);

        $ext = File::getStreamExt($stream);

        file_put_contents($directory . '/' . $filename . '.' . $ext, $stream);

        return $filename . '.' . $ext;
    }


    /**
     * 获取临时素材
     * Fetch item from WeChat server.
     * @param $appId
     * @param $mediaId
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getStream($appId, $mediaId)
    {
        $access_token = $this->auth->getAuthorizerToken($appId);
        $params = [
            'access_token' => $access_token,
            'media_id' => $mediaId,
        ];
        $response = $this->getHttp()->get(self::API_GET, $params);

        return $response->getBody();
    }


    /**
     * 上传临时素材
     * Upload temporary material.
     * @param $appId
     * @param $type
     * @param $path
     * @return \IopenWechat\Core\Collection
     * @throws InvalidArgumentException
     */
    public function upload($appId, $type, $path)
    {
        $access_token = $this->auth->getAuthorizerToken($appId);
        $params = [
            'access_token' => $access_token,
            'type' => $type,
        ];
        if (!file_exists($path) || !is_readable($path)) {
            throw new InvalidArgumentException("File does not exist, or the file is unreadable: '$path'");
        }

        if (!in_array($type, $this->allowTypes, true)) {
            throw new InvalidArgumentException("Unsupported media type: '{$type}'");
        }

        return $this->parseJSON('upload', [self::API_UPLOAD, ['media' => $path], $params]);
    }


    /**
     * 上传图片
     * Upload image.
     * @param $appId
     * @param $path
     * @return \IopenWechat\Core\Collection
     */
    public function uploadImage($appId, $path)
    {
        return $this->upload($appId, 'image', $path);
    }


    /**
     * Upload video.
     * @param $appId
     * @param $path
     * @return \IopenWechat\Core\Collection
     */
    public function uploadVideo($appId, $path)
    {
        return $this->upload($appId, 'video', $path);
    }

    /**
     * Upload voice.
     * @param $appId
     * @param $path
     * @return \IopenWechat\Core\Collection
     */
    public function uploadVoice($appId, $path)
    {
        return $this->upload($appId, 'voice', $path);
    }


    /**
     * Upload thumb.
     * @param $appId
     * @param $path
     * @return \IopenWechat\Core\Collection
     */
    public function uploadThumb($appId, $path)
    {
        return $this->upload($appId, 'thumb', $path);
    }
}
