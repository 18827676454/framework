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

namespace app\admin\command\task;

use app\admin\command\Task;

/**
 * Class TaskState
 * @package app\admin\command\task
 */
class TaskState extends Task
{

    protected function configure()
    {
        $this->setName('xtask:state')->setDescription('Message queue daemon process status');
    }

    /**
     * 执行指令
     * @param \think\console\Input $input
     * @param \think\console\Output $output
     * @return int|void|null
     */
    protected function execute(\think\console\Input $input, \think\console\Output $output)
    {
        if (($pid = $this->checkProcess()) > 0) {
            $output->info("Message queue daemon {$pid} is runing.");
        } else {
            $output->info('No message queue daemon is runing.');
        }
    }

}