<?php
// app/http/middleware/CheckLogin.php
namespace app\http\middleware;

use think\facade\Session;
use think\Response;

class CheckLogin
{
    public function handle($request, \Closure $next)
    {
        // 检查用户是否已登录
        if (!Session::has('username')) {
            // 未登录，重定向到登录页面
            return redirect('/admin/login.html');
            
        }

        // 用户已登录，继续处理请求
        return $next($request);
    }
}