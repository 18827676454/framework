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

use app\wechat\logic\Wechat;
use library\Controller;
use think\Db;

/**
 * 微信菜单管理
 * Class Menu
 * @package app\wechat\controller
 */
class Menu extends Controller
{
    /**
     * 指定操作的数据表
     * @var string
     */
    protected $table = 'WechatMenu';

    /**
     * 微信菜单的类型
     * @var array
     */
    protected $menuType = [
        'click'              => '匹配规则',
        'view'               => '跳转网页',
        'miniprogram'        => '打开小程序',
        'customservice'      => '转多客服',
        'scancode_push'      => '扫码推事件',
        'scancode_waitmsg'   => '扫码推事件且弹出“消息接收中”提示框',
        'pic_sysphoto'       => '弹出系统拍照发图',
        'pic_photo_or_album' => '弹出拍照或者相册发图',
        'pic_weixin'         => '弹出微信相册发图器',
        'location_select'    => '弹出地理位置选择器',
    ];

    /**
     * 显示菜单列表
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        if ($this->request->get('output') === 'json') {
            $where = [['keys', 'notin', ['subscribe', 'default']], ['status', 'eq', '1']];
            $keys = Db::name('WechatKeys')->where($where)->order('sort asc,id desc')->select();
            $this->success('获取数据成功!', ['menudata' => sysdata('menudata'), 'keysdata' => $keys]);
        }
        $this->title = '微信菜单定制';
        $this->menuTypes = $this->menuType;
        return $this->fetch();
    }

    /**
     * 微信菜单编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('data');
            if (empty($data)) { // 删除菜单
                try {
                    \We::WeChatMenu(Wechat::config())->delete();
                } catch (\Exception $e) {
                    $this->error('删除取消微信菜单失败，请稍候再试！' . $e->getMessage());
                }
                $this->success('删除并取消微信菜单成功！', '');
            }
            try {
                sysdata('menudata', json_decode($data, true));
                \We::WeChatMenu(Wechat::config())->create(['button' => sysdata('menudata')]);
            } catch (\Exception $e) {
                $this->error("微信菜单发布失败，请稍候再试！<br> {$e->getMessage()}");
            }
            $this->success('保存发布菜单成功！', '');
        }
    }

    /**
     * 取消菜单
     */
    public function cancel()
    {
        try {
            \We::WeChatMenu(Wechat::config())->delete();
        } catch (\Exception $e) {
            $this->error("菜单取消失败，请稍候再试！<br> {$e->getMessage()}");
        }
        $this->success('菜单取消成功，重新关注可立即生效！', '');
    }

}