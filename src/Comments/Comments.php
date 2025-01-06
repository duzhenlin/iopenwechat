<?php
/**
 * Created by Duzhenlin
 * Author: Duzhenlin
 * email: duzhenlin@vip.qq.com
 * Date: 2025/1/6
 * Time: 10:08
 */

namespace IopenWechat\Comments;

use IopenWechat\Core\AbstractAPI;
use IopenWechat\Core\App;
use Pimple\Container;

class Comments extends AbstractAPI
{
    /**
     * 查看指定文章的评论数据（新增接口）
     */
    const COMMENT_LIST_URL = 'https://api.weixin.qq.com/cgi-bin/comment/list';
    /**
     * 将评论标记精选（新增接口）
     */
    const COMMENT_OPEN_URL = 'https://api.weixin.qq.com/cgi-bin/comment/open';
    /**
     * 将评论标记精选（新增接口）
     */
    const COMMENT_CLOSE_URL = 'https://api.weixin.qq.com/cgi-bin/comment/close';
    /*
     *
     * */
    const COMMENT_DELETE_URL = 'https://api.weixin.qq.com/cgi-bin/comment/reply/delete';
    /**
     * 将评论标记精选（新增接口）
     */
    const COMMENT_MARKELECT_URL = 'https://api.weixin.qq.com/cgi-bin/comment/markelect';
    /**
     * @var App
     */
    protected $container;

    /**
     * Qrcode constructor.
     * @param Container $pimple
     */
    public function __construct(Container $pimple)
    {
        $this->container = $pimple;
    }

    public function getCommentList($appid, $msgDataId, $type, $count, $begin = 0, $index = 0)
    {
        $access_token = $this->container->auth->getAuthorizerToken($appid);
        $params       = [
            'msg_data_id' => $msgDataId,
            'index'       => $index,
            'begin'       => $begin,
            'count'       => $count,
            'type'        => $type,
        ];

        return $this->parseJSON('json', [self::COMMENT_LIST_URL . '?access_token=' . $access_token, $params]);
    }
}
