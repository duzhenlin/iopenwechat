<?php


namespace IopenWechat\Menu;

use IopenWechat\Core\AbstractAPI;


/**
 * Class Menu
 * @package IopenWechat\Menu
 */
class Menu extends AbstractAPI
{
    /**
     *
     */
    const API_CREATE             = 'https://api.weixin.qq.com/cgi-bin/menu/create';
    const API_GET                = 'https://api.weixin.qq.com/cgi-bin/menu/get';
    const API_DELETE             = 'https://api.weixin.qq.com/cgi-bin/menu/delete';
    const API_QUERY              = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info';
    const API_CONDITIONAL_CREATE = 'https://api.weixin.qq.com/cgi-bin/menu/addconditional';
    const API_CONDITIONAL_DELETE = 'https://api.weixin.qq.com/cgi-bin/menu/delconditional';
    const API_CONDITIONAL_TEST   = 'https://api.weixin.qq.com/cgi-bin/menu/trymatch';

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get all menus.
     * @param $appid
     * @return \IopenWechat\Core\Collection
     */
    public function all($appid)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        return $this->parseJSON('get', [self::API_GET, ['access_token' => $access_token]]);
    }


    /**
     *  Get current menus.
     * @param $appid
     * @return \IopenWechat\Core\Collection
     */
    public function current($appid)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        return $this->parseJSON('get', [self::API_QUERY, ['access_token' => $access_token]]);
    }


    /**
     * Add menu.
     * @param array $buttons
     * @param array $matchRule
     * @return \IopenWechat\Core\Collection
     */
    public function add(array $buttons, array $matchRule = [])
    {
        if (!empty($matchRule)) {
            return $this->parseJSON('json', [self::API_CONDITIONAL_CREATE, [
                'button'    => $buttons,
                'matchrule' => $matchRule,
            ]]);
        }

        return $this->parseJSON('json', [self::API_CREATE, ['button' => $buttons]]);
    }


    /**
     * Destroy menu.
     * @param null $menuId
     * @return \IopenWechat\Core\Collection
     */
    public function destroy($menuId = null)
    {
        if ($menuId !== null) {
            return $this->parseJSON('json', [self::API_CONDITIONAL_DELETE, ['menuid' => $menuId]]);
        }

        return $this->parseJSON('get', [self::API_DELETE]);
    }


    /**
     * Test conditional menu.
     * @param $userId
     * @return \IopenWechat\Core\Collection
     */
    public function test($userId)
    {
        return $this->parseJSON('json', [self::API_CONDITIONAL_TEST, ['user_id' => $userId]]);
    }
}
