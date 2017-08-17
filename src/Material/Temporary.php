<?php

namespace IopenWechat\Material;

use IopenWechat\Core\AbstractAPI;
use IopenWechat\Core\Exceptions\InvalidArgumentException;
use IopenWechat\Core\Helper\File;

/**
 * Class Temporary.
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
    const API_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/media/upload';
    protected $auth;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    /**
     * Download temporary material.
     *
     * @param  string $mediaId
     * @param  string $directory
     * @param  string $filename
     * @throws InvalidArgumentException
     * @return string
     */
    public function download($mediaId, $directory, $filename = '')
    {
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new InvalidArgumentException("Directory does not exist or is not writable: '$directory'.");
        }

        $filename = $filename ?: $mediaId;

        $stream = $this->getStream($mediaId);

        $ext = File::getStreamExt($stream);

        file_put_contents($directory . '/' . $filename . '.' . $ext, $stream);

        return $filename . '.' . $ext;
    }


    /**
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
//        $result = $this->parseJSON('get', [self::JSAPI_TICKET, $params]);

        $response = $this->getHttp()->get(self::API_GET, $params);

        return $response->getBody();
    }

    /**
     *  Upload temporary material.
     * @param $type
     * @param $path
     * @return \IopenWechat\Core\Collection
     * @throws InvalidArgumentException
     */
    public function upload($type, $path)
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new InvalidArgumentException("File does not exist, or the file is unreadable: '$path'");
        }

        if (!in_array($type, $this->allowTypes, true)) {
            throw new InvalidArgumentException("Unsupported media type: '{$type}'");
        }

        return $this->parseJSON('upload', [self::API_UPLOAD, ['media' => $path], ['type' => $type]]);
    }


    /**
     * Upload image.
     * @param $path
     * @return \IopenWechat\Core\Collection
     */
    public function uploadImage($path)
    {
        return $this->upload('image', $path);
    }


    /**
     * Upload video.
     * @param $path
     * @return \IopenWechat\Core\Collection
     */
    public function uploadVideo($path)
    {
        return $this->upload('video', $path);
    }

    /**
     * Upload voice.
     * @param $path
     * @return \IopenWechat\Core\Collection
     */
    public function uploadVoice($path)
    {
        return $this->upload('voice', $path);
    }


    /**
     * Upload thumb.
     * @param $path
     * @return \IopenWechat\Core\Collection
     */
    public function uploadThumb($path)
    {
        return $this->upload('thumb', $path);
    }
}
