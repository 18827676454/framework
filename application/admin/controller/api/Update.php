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

namespace app\admin\controller\api;

use app\admin\logic\Update as UpdateLogic;
use library\Controller;

/**
 * 系统更新接口
 * Class Update
 * @package app\admin\controller\api
 */
class Update extends Controller
{

    /**
     * 获取文件列表
     */
    public function get()
    {
        $this->success('获取当前文件列表成功！', UpdateLogic::get([
            'application/wechat',
            'application/admin',
            'public/static/plugs',
            'public/static/theme',
            'public/static/admin.js',
        ], [
            'application/admin/view/login/index.html',
        ]));
    }

    /**
     * 读取线上文件数据
     * @param string $encode
     */
    public function read($encode)
    {
        $file = env('root_path') . decode($encode);
        if (file_exists($file)) $this->error('获取文件内容失败！');
        $content = base64_encode(file_get_contents($file));
        $this->success('获取文件内容成功！', ['format' => 'base64', 'content' => $content]);
    }

    /**
     * 获取文件差异
     */
    public function diff()
    {
        $result = json_decode(http_get('https://framework.thinkadmin.top/admin/api.update/get'), true);
        $data = $result['data'];
        $new = \app\admin\logic\Update::get($data['paths'], $data['ignores']);
        $diff = \app\admin\logic\Update::contrast($result['data']['list'], $new['list']);
        $this->success('获取更新文件差异成功！', $diff);
    }

}