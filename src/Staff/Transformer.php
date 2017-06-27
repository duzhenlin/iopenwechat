<?php
namespace IopenWechat\Staff;
use IopenWechat\Message\Type\AbstractMessage;
use IopenWechat\Message\Type\Text;

class Transformer
{
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