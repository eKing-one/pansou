<?php

namespace app\admin\middleware;

use think\facade\Session;
use think\Response;

class Check
{
    use \liliuwei\think\Jump;
    public function handle($request, \Closure $next)
    {
        // 检查管理员是否已经登录
        if (!Session::has('username')) {
            // 如果没有登录，则重定向到登录页面
            return $this->error('请先登录','/admin.php/login/index');
        }

        // 登录验证通过后继续处理请求
        return $next($request);
    }
}