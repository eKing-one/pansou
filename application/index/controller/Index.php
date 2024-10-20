<?php

namespace app\index\controller;

use app\common\controller\BaseController;


class Index extends BaseController
{
    public function index()
    {

        //搜索关键字按热度倒叙
        $hot_keywords = db('keywords')  // 指定表名
            ->where('is_audit', 1)  // 添加条件，is_audit 等于 1
            ->order('search_count', 'desc')  // 按照 search_count 降序排列
            ->select();  // 执行查询并获取结果
        $this->assign('hot_keywords', $hot_keywords);
        //查询总数据条数
        $count = db('resources')->count();
        $this->assign('count', $count);

        return $this->fetch('index');
    }
    public function updateHistory($keyword)
    {
        // 获取请求中的关键词
        if (empty($keyword)) {
            return "null";
        }

        // 检查该关键字是否已经存在
        $existing = db('keywords')->where('keyword', $keyword)->find();

        if ($existing) {
            // 如果存在，更新搜索次数和时间
            db('keywords')->where('keyword', $keyword)->update([
                'search_count' => $existing['search_count'] + 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // 如果不存在，插入新记录
            db('keywords')->insert([
                'keyword' => $keyword,
                'search_count' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return "ok";
    }
}
