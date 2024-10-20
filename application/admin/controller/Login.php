<?php
// app/admin/controller/Login.php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\facade\Session; // 导入 Session Facade
// use think\captcha\Captcha;
use app\common\model\User as UserModel; // 使用别名来避免冲突

class Login extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');

        // 这里进行用户名和密码的验证
        $user = UserModel::where('username', $username)->find();

        if ($user && password_verify($password, $user->password)) {
            // 验证通过，设置会话
            Session::set('username', $user->username);
            return $this->success('登录成功', '/admin/index');
        } else {
            return $this->error('用户名或密码错误');
        }
    }

    public function logout()
    {
        // 清除会话
        Session::clear();
        return $this->success('退出成功', '/admin/login');
    }
    // public function verify()
    // {
    //     $captcha = new Captcha();
    //     return $captcha->entry();    
    // }
}