<?php
namespace IopenWechat\Staff;
use IopenWechat\Core\Exceptions\RuntimeException;
use IopenWechat\Message\Type\Text;

class MessageBuilder
{
    /**
     * Message to send.
     *
     * @var \IopenWechat\Message\Type\AbstractMessage;
     */
    protected $message;

    /**
     * Message target user open id.
     *
     * @var string
     */
    protected $to;

    /**
     * Message sender staff id.
     *
     * @var string
     */
    protected $account;

    /**
     * Staff instance.
     *
     * @var \IopenWechat\Staff\Staff
     */
    protected $staff;

    /**
     * MessageBuilder constructor.
     *
     * @param \IopenWechat\Staff\Staff $staff
     */
    public function __construct(Staff $staff)
    {
        $this->staff = $staff;
    }

    /**
     * Set message to send.
     *
     * @param string|AbstractMessage $message
     *
     * @return MessageBuilder
     *
     * @throws InvalidArgumentException
     */
    public function message($message)
    {
        if (is_string($message)) {
            $message = new Text(['content' => $message]);
        }

        $this->message = $message;

        return $this;
    }

    /**
     * Set target user open id.
     *
     * @param string $openId
     *
     * @return MessageBuilder
     */
    public function to($openId)
    {
        $this->to = $openId;

        return $this;
    }

    public function send()
    {
        if (empty($this->message)) {
            throw new RuntimeException('No message to send.');
        }

        $transformer = new Transformer();

//        if ($this->message instanceof RawMessage) {
//            $message = $this->message->get('content');
//        } else {
//            $content = $transformer->transform($this->message);
//
//            $message = array_merge([
//                'touser' => $this->to,
//                'customservice' => ['kf_account' => $this->account],
//            ], $content);
//        }
        $content = $transformer->transform($this->message);

        $message = array_merge([
            'touser' => $this->to,
            'customservice' => ['kf_account' => $this->account],
        ], $content);

        return $this->staff->send($message);
    }
}