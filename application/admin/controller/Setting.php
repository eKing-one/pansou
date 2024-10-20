<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Config as ConfigModel;
use think\facade\Cache;
class Setting extends Controller
{
    // 配置项的中文名称映射
    protected $configNames = [
        'site_title' => '网站标题',
        'site_description' => '网站描述',
        'site_keywords' => '关键词',
        'icp_number' => '备案号',
        'contact_info' => '联系方式',
        'email_address' => '邮箱地址',
        'wechat_qrcode_url' => '微信公众号二维码链接',
        'site_logo_url' => '网站Logo URL',
        'footer_copyright' => '网站底部版权信息',
        'maintenance_mode' => '是否开启维护模式',
    ];

    public function index()
    {
        $config = ConfigModel::all();
        $this->assign('config', $config);
        $this->assign('configNames', $this->configNames); // 将映射数组传递给视图
        return $this->fetch();
    }

    public function save(Request $request)
    {
        $data = $request->post();

        foreach ($data as $name => $value) {
            ConfigModel::updateOrCreate(['name' => $name], ['value' => $value]);
        }
        // 清除缓存
        Cache::clear();
        return $this->success('保存成功');
    }
}