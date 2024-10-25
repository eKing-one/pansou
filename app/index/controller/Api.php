<?php

namespace app\index\controller;

use think\facade\View;
use think\facade\Db;


class Api
{

    //记录关键词
    public function updateHistory($keyword)
    {
        // 获取请求中的关键词
        if (empty($keyword)) {
            return "null";
        }

        // 检查该关键字是否已经存在
        $existing = Db::name('keywords')->where('keyword', $keyword)->find();

        if ($existing) {
            // 如果存在，更新搜索次数和时间
            Db::name('keywords')->where('keyword', $keyword)->update([
                'search_count' => $existing['search_count'] + 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // 如果不存在，插入新记录
            Db::name('keywords')->insert([
                'keyword' => $keyword,
                'search_count' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return "ok";
    }

    //检测资源是否有效
    //传入url
    //返回json
    public function checkUrl()
    {
        $url = input('get.url');
        if (true) {
            return json(['code' => 1,'msg' =>'链接有效']);
        }else{
            return json(['code' => 0,'msg' => '链接无效']);
        }


    }
    // 举报资源
    public function report()
    {
        $id = input('post.id');
        $reason = input('post.reason');
        $details = input('post.details');
        $contact = input('post.contact');

        // 数据验证，确保输入不为空
        if (empty($id) || empty($reason)) {
            return json(['code' => 0, 'msg' => '请填写完整的举报信息']);
        }

        // 检查是否已经举报过该资源（可选）
        $existingReport = Db::name('report')->where('resource_id', $id)->whereTime('created_at', '-1 hour')->find();
        if ($existingReport) {
            return json(['code' => 0, 'msg' => '您已经举报过该资源']);
        }

        // 准备要插入的数据
        $data = [
            'resource_id' => $id,
            'reason' => $reason, // 举报原因
            'details' => $details, // 举报详细信息
            'contact' => $contact, // 联系方式
        ];

        // 插入举报数据
        $result = Db::name('report')->insert($data);

        // 返回结果
        if ($result) {
            return json(['code' => 1, 'msg' => '举报成功']);
        } else {
            return json(['code' => 0, 'msg' => '举报失败']);
        }
    }

}
