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

namespace app\store\controller;

use library\Controller;
use library\tools\Data;
use think\Db;

/**
 * 商城商品管理
 * Class Goods
 * @package app\store\controller
 */
class Goods extends Controller
{
    /**
     * 指定数据表
     * @var string
     */
    protected $table = 'StoreGoods';

    /**
     * 商品列表
     * @return mixed
     */
    public function index()
    {
        $this->title = '商品管理';
        $query = $this->_query($this->table)->like('title')->equal('status');
        return $this->_page($query->db()->order('id desc'));
    }

    /**
     * 数据列表处理
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _index_page_filter(&$data)
    {
        $where = [['status', 'eq', '1'], ['goods_id', 'in', array_unique(array_column($data, 'id'))]];
        $list = Db::name('StoreGoodsList')->where($where)->select();
        $data = array_map(function ($vo) use ($list) {
            $vo['list'] = [];
            foreach ($list as $goods) if ($goods['goods_id'] === $vo['id']) array_push($vo['list'], $goods);
            return $vo;
        }, $data);
    }


    /**
     * 添加商品信息
     * @return mixed
     */
    public function add()
    {
        $this->title = '添加商品信息';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑商品信息
     * @return mixed
     */
    public function edit()
    {
        $this->title = '编辑商品信息';
        return $this->_form($this->table, 'form');
    }

    /**
     * 表单数据处理
     * @param array $data
     */
    protected function _form_filter(&$data)
    {
        if ($this->request->isGet() && !empty($data['id'])) {
            $defaultValues = Db::name('StoreGoodsList')->where(['goods_id' => $data['id']])
                ->column('goods_spec,goods_id,status,price_market market,price_selling selling,number_virtual virtual');
            $this->assign('defaultValues', json_encode($defaultValues, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * 表单结果处理
     * @param boolean $result
     * @param array $data
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function _form_result($result, $data)
    {
        if ($result && $this->request->isPost()) {
            $goods_id = empty($data['id']) ? Db::name($this->table)->getLastInsID() : $data['id'];
            Db::name('StoreGoodsList')->where(['goods_id' => $goods_id])->update(['status' => '0']);
            foreach (json_decode($data['lists'], true) as $vo) {
                Data::save('StoreGoodsList', [
                    'goods_id'       => $goods_id,
                    'goods_spec'     => $vo[0]['key'],
                    'price_market'   => $vo[0]['market'],
                    'price_selling'  => $vo[0]['selling'],
                    'number_virtual' => $vo[0]['virtual'],
                    'status'         => $vo[0]['status'] ? 1 : 0,
                ], 'goods_spec', ['goods_id' => $goods_id]);
            }
            $this->success('商品编辑成功！', 'javascript:history.back()');
        }
    }

    /**
     * 商品禁用
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 商品禁用
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除商品
     */
    public function del()
    {
        $this->_delete($this->table);
    }

}