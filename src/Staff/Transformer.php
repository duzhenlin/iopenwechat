<?php
namespace IopenWechat\Staff;
use IopenWechat\Message\Type\AbstractMessage;
use IopenWechat\Message\Type\Text;

/**
 * Class Transformer
 * @package IopenWechat\Staff
 */
class Transformer
{
    /**
     * @param $message
     * @return array
     */
    public function transform($message)
    {
        if(is_array($message)){

        }else{
            if(is_string($message)){
                $message = new Text(['content' => $message]);
            }
            $class = get_class($message);
        }
        $handle = 'transform' . substr($class, strlen('IopenWechat\Message\Type\\'));

        return method_exists($this, $handle) ? $this->$handle($message) : [];
    }

    /**
     * @param AbstractMessage $message
     * @return array
     */
    protected function transformText(AbstractMessage $message)
    {
        return [
            'msgtype' => 'text',
            'text' => [
                'content' => $message->get('content')
            ]
        ];
    }
}