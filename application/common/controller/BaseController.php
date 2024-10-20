<?php
// app/common/controller/BaseController.php
namespace app\common\controller;

use think\Controller;
use app\common\service\ConfigService;

class BaseController extends Controller
{
    public function initialize()
    {
        parent::initialize();

        // 批量获取配置项
        $config = [
            'site_title'       => ConfigService::getConfig('site_title'),
            'site_keywords'    => ConfigService::getConfig('site_keywords'),
            'site_description' => ConfigService::getConfig('site_description'),
            'icp_number'       => ConfigService::getConfig('icp_number'),
            'contact_info'     => ConfigService::getConfig('contact_info'),
            'email_address'    => ConfigService::getConfig('email_address'),
            'wechat_qrcode_url'=> ConfigService::getConfig('wechat_qrcode_url'),
            'site_logo_url'    => ConfigService::getConfig('site_logo_url'),
            'footer_copyright' => ConfigService::getConfig('footer_copyright'),
            'maintenance_mode' => ConfigService::getConfig('maintenance_mode'),
        ];

        // 将配置项传递到模板
        $this->assign($config);

        // 根据配置做一些全局逻辑处理（例如维护模式）
        if ($config['maintenance_mode'] == 1) {
            return $this->fetch('public/maintenance');  // 跳转到维护模式页面
        }
    }
}
