<?php
// 应用公共文件
use think\facade\Db;


// 从数据库中读取配置
function getConfig($key)
{

    $config = Db::name('config')->where('name', $key)->value('value');
    return $config;
}

