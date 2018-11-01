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

namespace app\wechat\logic;

/**
 * 微信处理管理
 * Class Wechat
 * @package app\wechat\logic
 */
class Wechat
{

    /**
     * 获取微信支付配置
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function config()
    {
        $options = [
            'token'          => sysconf('wechat_token'),
            'appid'          => sysconf('wechat_appid'),
            'appsecret'      => sysconf('wechat_appsecret'),
            'encodingaeskey' => sysconf('wechat_encodingaeskey'),
            'mch_id'         => sysconf('wechat_mch_id'),
            'mch_key'        => sysconf('wechat_mch_key'),
            'cachepath'      => env('cache_path') . 'wechat' . DIRECTORY_SEPARATOR,
        ];
        if (sysconf('wechat_mch_ssl_type') === 'p12') {
            $options['ssl_p12'] = sysconf('wechat_mch_ssl_p12');
        } else {
            $options['ssl_key'] = sysconf('wechat_mch_ssl_key');
            $options['ssl_cer'] = sysconf('wechat_mch_ssl_cer');
        }
        return $options;
    }

    /**
     * 获取当前公众号APPID
     * @return bool|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function getAppid()
    {
        return sysconf('wechat_appid');
    }

}