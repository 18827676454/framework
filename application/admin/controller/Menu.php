<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://framework.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/framework
// +----------------------------------------------------------------------

namespace app\admin\controller;

use library\Controller;
use library\tools\Data;

/**
 * 系统菜单管理
 * Class Menu
 * @package app\admin\controller
 */
class Menu extends Controller
{

    /**
     * 当前操作数据库
     * @var string
     */
    protected $table = 'SystemMenu';

    /**
     * 系统菜单显示
     * @return array
     */
    public function index()
    {
        $this->title = '系统菜单管理';
        return $this->_page($this->table, false);
    }

    /**
     * 列表数据处理
     * @param array $data
     */
    protected function _index_page_filter(&$data)
    {
        foreach ($data as &$vo) {
            if ($vo['url'] !== '#') {
                $vo['url'] = url($vo['url']) . (empty($vo['params']) ? '' : "?{$vo['params']}");
            }
            $vo['ids'] = join(',', Data::getArrSubIds($data, $vo['id']));
        }
        $data = Data::arr2table($data);
    }

    /**
     * 启用菜单
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 禁用菜单
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 删除菜单
     */
    public function del()
    {
        $this->_delete($this->table);
    }

}