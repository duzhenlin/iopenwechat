<?php
namespace IopenWechat\Message\Type;

class Text extends AbstractMessage
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $msgType = 'text';

    /**
     * Message Properties.
     *
     * @var array
     */
    protected $properties = ['content'];
}