<?php

namespace app\index\controller;

use think\facade\Db;
use think\facade\View;
use app\admin\service\ConfigService;
class Index
{
    public function index()
    {
        //搜索关键字按热度倒叙
        $hot_keywords = Db::name('keywords')  // 指定表名
            ->where('is_audit', 1)  // 添加条件，is_audit 等于 1
            ->order('search_count', 'desc')  // 按照 search_count 降序排列
            ->select();  // 执行查询并获取结果
        View::assign('hot_keywords', $hot_keywords);
        //查询总数据条数
        $count = Db::name('resources')->count();
        View::assign('count', $count);
        return View::fetch('index');
    }
    
}
