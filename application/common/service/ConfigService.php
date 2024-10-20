<?php
namespace app\common\service;

use think\facade\Cache;
use app\common\model\Config as ConfigModel;

class ConfigService
{
    public static function getConfig($key)
    {
        // 先从缓存中读取配置
        $config = Cache::get('config_' . $key);
        
        if (!$config) {
            // 如果缓存中没有，查询数据库
            $config = ConfigModel::where('name', $key)->value('value');
            
            if ($config) {
                // 保存到缓存
                Cache::set('config_' . $key, $config);
            }
        }

        return $config;
    }
}
