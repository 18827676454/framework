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

namespace app\index\controller;

use app\admin\logic\Message;
use library\Controller;

/**
 * 应用入口
 * Class Index
 * @package app\index\controller
 */
class Index extends Controller
{
    /**
     * 入口跳转链接
     */
    public function index()
    {
        $this->redirect('@admin');
    }

    public function test()
    {
        $result = Message::add(
            '有新的用户申请审核！',
            '小小邹，13617341324，门店号',
            '/admin/index/main.html?spm=m-1',
            'admin/wechat/index'
        );
        dump($result);
    }
}
