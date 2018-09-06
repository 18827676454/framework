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

use app\admin\logic\Auth;
use library\Controller;
use library\tools\Data;
use think\Db;

/**
 * 系统节点管理
 * Class Node
 * @package app\admin\controller
 */
class Node extends Controller
{
    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'SystemNode';

    /**
     * 显示节点列表
     * @return mixed
     */
    public function index()
    {
        $this->title = '系统节点管理';
        $this->nodes = Data::arr2table(Auth::get(), 'node', 'pnode');
        $groups = [];
        foreach ($this->nodes as $node) {
            $pnode = explode('/', $node['node'])[0];
            if ($node['node'] === $pnode) {
                $groups[$pnode]['node'] = $node;
            }
            $groups[$pnode]['list'][] = $node;
        }
        $this->groups = $groups;
        return $this->fetch();
    }

    /**
     * 清理无效的节点数据
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function clear()
    {
        $where = [['node', 'not in', array_keys(Auth::get())]];
        if (false !== Db::name($this->table)->where($where)->delete()) {
            $this->success('清理无效节点记录成功！', '');
        }
        $this->error('清理无效记录失败，请稍候再试！');
    }

    /**
     * 更新数据记录
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function save()
    {
        if ($this->request->isPost()) {
            list($post, $data) = [$this->request->post(), []];
            foreach ($post['list'] as $vo) {
                if (!empty($vo['node'])) {
                    $data['node'] = $vo['node'];
                    $data[$vo['name']] = $vo['value'];
                }
            }
            empty($data) || Data::save($this->table, $data, 'node');
            $this->success('参数保存成功！', '');
        }
        $this->error('访问异常，请重新进入...');
    }

}