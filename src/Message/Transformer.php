<?php

namespace IopenWechat\Message;

use IopenWechat\Message\Type\AbstractMessage;
use IopenWechat\Message\Type\Text;
use IopenWechat\Message\Type\News;

class Transformer
{
    protected $to;
    protected $from;

    public function transform($message)
    {
        if (is_array($message)) {
            $class = News::class;
        } else {
            if (is_string($message)) {
                $message = new Text(['content' => $message]);
            }
            $class = get_class($message);
        }
        $handle = 'transform' . substr($class, strlen('IopenWechat\Message\Type\\'));

        return method_exists($this, $handle) ? $this->$handle($message) : [];
    }

    protected function transformText(AbstractMessage $message)
    {
        return [
            'Content' => $message->get('content'),
        ];
    }

    /**
     * Transform news message.
     *
     * @param array $news
     *
     * @return array
     */
    public function transformNews($news)
    {
        $articles = [];

        if (!is_array($news)) {
            $news = [$news];
        }

        foreach ($news as $item) {
            $articles[] = [
                'Title' => $item->get('title'),
                'Description' => $item->get('description'),
                'Url' => $item->get('url'),
                'PicUrl' => $item->get('picurl'),
            ];
        }

        return [
            'ArticleCount' => count($articles),
            'Articles' => $articles,
        ];
    }

    /**
     * Transform image message.
     *
     * @return array
     */
    public function transformImage(AbstractMessage $message)
    {
        return [
            'Image' => [
                'MediaId' => $message->get('media_id'),
            ],
        ];
    }

    /**
     * Transform video message.
     *
     * @return array
     */
    public function transformVideo(AbstractMessage $message)
    {
        $response = [
            'Video' => [
                'MediaId' => $message->get('media_id'),
                'Title' => $message->get('title'),
                'Description' => $message->get('description'),
            ],
        ];

        return $response;
    }

    /**
     * Transform voice message.
     *
     * @return array
     */
    public function transformVoice(AbstractMessage $message)
    {
        return [
            'Voice' => [
                'MediaId' => $message->get('media_id'),
            ],
        ];
    }

    /**
     * Transform transfer message.
     *
     * @return array
     */
    public function transformTransfer(AbstractMessage $message)
    {
        $response = [];

        // 指定客服
        if ($message->get('account')) {
            $response['TransInfo'] = [
                'KfAccount' => $message->get('account'),
            ];
        }

        return $response;
    }

}