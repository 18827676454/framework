<?php

// +----------------------------------------------------------------------
// | framework
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://framework.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/framework
// +----------------------------------------------------------------------

namespace app\wechat\controller;

use library\Controller;

/**
 * 微信粉丝管理
 * Class Fans
 * @package app\wechat\controller
 */
class Fans extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'WechatFans';

    /**
     * 微信粉丝管理
     * @return array
     */
    public function index()
    {
        $this->title = '微信粉丝管理';
        $query = $this->_query($this->table)->like('nickname')->equal('subscribe,is_black');
        return $this->_page($query->dateBetween('subscribe_at')->db()->order('subscribe_time desc'));
    }

    /**
     * 微信粉丝列表处理
     * @param $data
     */
    protected function _index_page_filter(&$data)
    {
        foreach ($data as &$user) foreach (['country', 'province', 'city', 'nickname', 'remark'] as $k) {
            isset($user[$k]) && $user[$k] = emoji_decode($user[$k]);
        }
    }

}