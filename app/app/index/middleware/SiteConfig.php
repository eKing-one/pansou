<?php
declare (strict_types = 1);

namespace app\index\middleware;

use think\facade\Db;
use think\facade\View;
use Closure;
use think\Response;

class SiteConfig
{
    public function getConfig(string $key): ?string
    {
        return Db::name('config')->where('name', $key)->value('value');
    }

    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $config = [
            'site_title'       => $this->getConfig('site_title'),
            'site_keywords'    => $this->getConfig('site_keywords'),
            'site_description' => $this->getConfig('site_description'),
            'icp_number'       => $this->getConfig('icp_number'),
            'contact_info'     => $this->getConfig('contact_info'),
            'email_address'    => $this->getConfig('email_address'),
            'wechat_qrcode_url'=> $this->getConfig('wechat_qrcode_url'),
            'site_logo_url'    => $this->getConfig('site_logo_url'),
            'footer_copyright' => $this->getConfig('footer_copyright'),
            'maintenance_mode' => $this->getConfig('maintenance_mode'),
        ];

        // 将配置项传递到模板
        View::assign($config);

        // 根据配置做一些全局逻辑处理（例如维护模式）
        if ($config['maintenance_mode'] == 1) {
            // 如果处于维护模式，则返回维护页面
            return View::fetch('public/maintenance');
        }

        // 继续处理请求
        return $next($request);
    }
}