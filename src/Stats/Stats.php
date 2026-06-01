<?php
/**
 * Created by PhpStorm.
 * User: duzhenlin
 * Date: 2017/6/27
 * Time: 16:07
 *
 * @author      duzhenlin <duzhenlin@vip.qq.com>
 */

namespace IopenWechat\Stats;

use IopenWechat\Core\AbstractAPI;

class Stats extends AbstractAPI
{
    // 获取用户增减数据
    const  API_USER_SUMMARY = 'https://api.weixin.qq.com/datacube/getusersummary';
    // 获取累计用户数据
    const  API_USER_CUMULATE = 'https://api.weixin.qq.com/datacube/getusercumulate';
    // 获取图文群发每日数据
    const  API_ARTICLE_SUMMARY = 'https://api.weixin.qq.com/datacube/getarticlesummary';
    // 获取图文群发总数据
    const  API_ARTICLE_TOTAL = 'https://api.weixin.qq.com/datacube/getarticletotal';
    // 获取发表内容概况总数据。微信已下线旧 getarticlesummary，后续账号维度图文概况使用该新接口。
    const  API_BIZ_SUMMARY = 'https://api.weixin.qq.com/datacube/getbizsummary';
    // 获取发表内容发表详细数据。微信已下线旧 getarticletotal，后续文章维度明细使用该新接口。
    const  API_ARTICLE_TOTAL_DETAIL = 'https://api.weixin.qq.com/datacube/getarticletotaldetail';
    // 获取发表内容每日阅读数据。该接口用于补充阅读来源明细，默认队列暂不调用。
    const  API_ARTICLE_READ = 'https://api.weixin.qq.com/datacube/getarticleread';
    // 获取发表内容每日分享数据。该接口用于补充分享明细，默认队列暂不调用。
    const  API_ARTICLE_SHARE = 'https://api.weixin.qq.com/datacube/getarticleshare';
    // 获取图文统计数据
    const  API_USER_READ_SUMMARY = 'https://api.weixin.qq.com/datacube/getuserread';
    // 获取图文统计分时数据
    const  API_USER_READ_HOURLY = 'https://api.weixin.qq.com/datacube/getuserreadhour';
    // 获取图文分享转发数据
    const  API_USER_SHARE_SUMMARY = 'https://api.weixin.qq.com/datacube/getusershare';
    // 获取图文分享转发分时数据
    const  API_USER_SHARE_HOURLY = 'https://api.weixin.qq.com/datacube/getusersharehour';
    // 获取消息发送概况数据
    const  API_UPSTREAM_MSG_SUMMARY = 'https://api.weixin.qq.com/datacube/getupstreammsg';
    // 获取消息分送分时数据
    const  API_UPSTREAM_MSG_HOURLY = 'https://api.weixin.qq.com/datacube/getupstreammsghour';
    // 获取消息发送周数据
    const  API_UPSTREAM_MSG_WEEKLY = 'https://api.weixin.qq.com/datacube/getupstreammsgweek';
    // 获取消息发送月数据
    const  API_UPSTREAM_MSG_MONTHLY = 'https://api.weixin.qq.com/datacube/getupstreammsgmonth';
    // 获取消息发送分布数据
    const  API_UPSTREAM_MSG_DIST_SUMMARY = 'https://api.weixin.qq.com/datacube/getupstreammsgdist';
    // 获取消息发送分布周数据
    const  API_UPSTREAM_MSG_DIST_WEEKLY = 'https://api.weixin.qq.com/datacube/getupstreammsgdistweek';
    // 获取消息发送分布月数据
    const  API_UPSTREAM_MSG_DIST_MONTHLY = 'https://api.weixin.qq.com/datacube/getupstreammsgdistmonth?';
    // 获取接口分析数据
    const  API_INTERFACE_SUMMARY = 'https://api.weixin.qq.com/datacube/getinterfacesummary';
    // 获取接口分析分时数据
    const  API_INTERFACE_SUMMARY_HOURLY = 'https://api.weixin.qq.com/datacube/getinterfacesummaryhour';
    // 拉取卡券概况数据接口
    const  API_CARD_SUMMARY = 'https://api.weixin.qq.com/datacube/getcardbizuininfo';
    // 获取免费券数据接口
    const  API_FREE_CARD_SUMMARY = 'https://api.weixin.qq.com/datacube/getcardcardinfo';
    // 拉取会员卡数据接口
    const  API_MEMBER_CARD_SUMMARY = 'https://api.weixin.qq.com/datacube/getcardmembercardinfo';

    protected $auth;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    /**
     * 获取用户增减数据.
     * @param $appId
     * @param $from
     * @param $to
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function userSummary($appId, $from, $to)
    {
        return $this->query($appId, self::API_USER_SUMMARY, $from, $to);
    }

    /**
     * 获取累计用户数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function userCumulate($appId, $from, $to)
    {
        return $this->query($appId, self::API_USER_CUMULATE, $from, $to);
    }

    /**
     * 获取图文群发每日数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function articleSummary($appId, $from, $to)
    {
        return $this->query($appId, self::API_ARTICLE_SUMMARY, $from, $to);
    }

    /**
     * 获取图文群发总数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function articleTotal($appId, $from, $to)
    {
        return $this->query($appId, self::API_ARTICLE_TOTAL, $from, $to);
    }

    /**
     * 获取发表内容概况总数据.
     *
     * 这是微信 2026 新增/调整后的图文统计接口之一，用于替代已下线的
     * getarticlesummary。返回的是账号维度的汇总口径，不再完全等同旧接口字段。
     *
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function bizSummary($appId, $from, $to)
    {
        return $this->query($appId, self::API_BIZ_SUMMARY, $from, $to);
    }

    /**
     * 获取发表内容发表详细数据.
     *
     * 这是微信 2026 新增/调整后的文章维度明细接口，用于替代已下线的
     * getarticletotal。新接口返回 detail_list、read_user、share_user、
     * collection_user 等新口径字段，调用方需要按新字段入库。
     *
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function articleTotalDetail($appId, $from, $to)
    {
        return $this->query($appId, self::API_ARTICLE_TOTAL_DETAIL, $from, $to);
    }

    /**
     * 获取发表内容每日阅读数据.
     *
     * 默认统计队列先以 articleTotalDetail 作为文章明细主数据源；该方法保留给
     * 后续需要单独拉取阅读来源明细时使用。
     *
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function articleRead($appId, $from, $to)
    {
        return $this->query($appId, self::API_ARTICLE_READ, $from, $to);
    }

    /**
     * 获取发表内容每日分享数据.
     *
     * 默认统计队列先以 articleTotalDetail 作为文章明细主数据源；该方法保留给
     * 后续需要单独拉取分享明细时使用。
     *
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function articleShare($appId, $from, $to)
    {
        return $this->query($appId, self::API_ARTICLE_SHARE, $from, $to);
    }

    /**
     * 获取图文统计数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function userReadSummary($appId, $from, $to)
    {
        return $this->query($appId, self::API_USER_READ_SUMMARY, $from, $to);
    }

    /**
     * 获取图文统计分时数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function userReadHourly($appId, $from, $to)
    {
        return $this->query($appId, self::API_USER_READ_HOURLY, $from, $to);
    }

    /**
     * 获取图文分享转发数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function userShareSummary($appId, $from, $to)
    {
        return $this->query($appId, self::API_USER_SHARE_SUMMARY, $from, $to);
    }

    /**
     * 获取图文分享转发分时数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function userShareHourly($appId, $from, $to)
    {
        return $this->query($appId, self::API_USER_SHARE_HOURLY, $from, $to);
    }

    /**
     * 获取消息发送概况数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function upstreamMessageSummary($appId, $from, $to)
    {
        return $this->query($appId, self::API_UPSTREAM_MSG_SUMMARY, $from, $to);
    }

    /**
     * 获取消息分送分时数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function upstreamMessageHourly($appId, $from, $to)
    {
        return $this->query($appId, self::API_UPSTREAM_MSG_HOURLY, $from, $to);
    }

    /**
     * 获取消息发送周数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function upstreamMessageWeekly($appId, $from, $to)
    {
        return $this->query($appId, self::API_UPSTREAM_MSG_WEEKLY, $from, $to);
    }

    /**
     * 获取消息发送月数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function upstreamMessageMonthly($appId, $from, $to)
    {
        return $this->query($appId, self::API_UPSTREAM_MSG_MONTHLY, $from, $to);
    }

    /**
     * 获取消息发送分布数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function upstreamMessageDistSummary($appId, $from, $to)
    {
        return $this->query($appId, self::API_UPSTREAM_MSG_DIST_SUMMARY, $from, $to);
    }

    /**
     * 获取消息发送分布周数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function upstreamMessageDistWeekly($appId, $from, $to)
    {
        return $this->query($appId, self::API_UPSTREAM_MSG_DIST_WEEKLY, $from, $to);
    }

    /**
     * 获取消息发送分布月数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function upstreamMessageDistMonthly($appId, $from, $to)
    {
        return $this->query($appId, self::API_UPSTREAM_MSG_DIST_MONTHLY, $from, $to);
    }

    /**
     * 获取接口分析数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function interfaceSummary($appId, $from, $to)
    {
        return $this->query($appId, self::API_INTERFACE_SUMMARY, $from, $to);
    }

    /**
     * 获取接口分析分时数据.
     * @param        $appId
     * @param string $from
     * @param string $to
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function interfaceSummaryHourly($appId, $from, $to)
    {
        return $this->query($appId, self::API_INTERFACE_SUMMARY_HOURLY, $from, $to);
    }

    /**
     * 拉取卡券概况数据接口.
     * @param        $appId
     * @param string $from
     * @param string $to
     * @param int    $condSource
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function cardSummary($appId, $from, $to, $condSource = 0)
    {
        $ext = [
            'cond_source' => intval($condSource),
        ];
        return $this->query($appId, self::API_CARD_SUMMARY, $from, $to, $ext);
    }

    /**
     * 获取免费券数据接口.
     * @param        $appId
     * @param string $from
     * @param string $to
     * @param int    $condSource
     * @param string $cardId
     *
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function freeCardSummary($appId, $from, $to, $condSource = 0, $cardId = '')
    {
        $ext = [
            'cond_source' => intval($condSource),
            'card_id' => $cardId,
        ];
        return $this->query($appId, self::API_FREE_CARD_SUMMARY, $from, $to, $ext);
    }


    /**
     * 拉取会员卡数据接口.
     * @param     $appId
     * @param     $from
     * @param     $to
     * @param int $condSource
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    public function memberCardSummary($appId, $from, $to, $condSource = 0)
    {
        $ext = [
            'cond_source' => intval($condSource),
        ];
        return $this->query($appId, self::API_MEMBER_CARD_SUMMARY, $from, $to, $ext);
    }


    /**
     * 查询数据
     * @param $appId
     * @param $api
     * @param $from
     * @param $to
     * @param array $ext
     * @return \IopenWechat\Core\Collection
     * @throws \IopenWechat\Core\Exceptions\HttpException
     */
    protected function query($appId, $api, $from, $to, array $ext = [])
    {
        $access_token = $this->auth->getAuthorizerToken($appId);
        $params = [
            'begin_date' => $from,
            'end_date' => $to,
        ];
        if (!empty($ext)) {
            $params = array_merge($params, $ext);
        }
        return $this->parseJSON('json', [$api . '?access_token=' . $access_token, $params]);
    }
}
