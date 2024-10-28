<?php
// app/admin/controller/Login.php
namespace app\admin\controller;

use think\facade\View;
use think\facade\Session; // 导入 Session Facade
use think\captcha\facade\Captcha;
use app\admin\model\User as UserModel; // 使用别名来避免冲突

class Login
{
    use \liliuwei\think\Jump; 
    public function index()
    {
        return View::fetch('login');
    }

    public function login()
    {
        $username = input('post.username');
        $password = input('post.password');
        // 获取验证码
        $captcha = input('post.captcha');

        // 验证验证码是否正确
        if(!captcha_check($captcha)){
            return $this->error('验证码错误');
           };
        $user = UserModel::where('username', $username)->find();
        
        if ($user && password_verify($password, $user->password)) {
            // 验证通过，设置会话
            Session::set('username', $user->username);
            Session::set('user_id', $user->id);
            return $this->success('登录成功', 'index/index');
        } else {
            return $this->error('用户名或密码错误');
        }
        
    }

    public function logout()
    {
        // 清除会话
        Session::clear();
        return $this->success('退出成功', '/admin.php/login/index');
    }
    
}