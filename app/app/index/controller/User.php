<?php

namespace app\index\controller;

use think\facade\View;
use think\facade\Db;

class User
{
    public function index()
    {
        View::assign('title', '会员中心');
        return View::fetch('index');
    }
    public function login()
    {
        //登录逻辑
        if (request()->isPost()) {
            $data = input('post.');
            $validate = validate('User');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $user = Db::name('users')->where('username', $data['username'])->find();
            if ($user) {
                if ($user['password'] == md5($data['password'])) {
                    session('user', $user);
                    $this->success('登录成功', 'index');
                } else {
                    $this->error('密码错误');
                }
            } else {
                $this->error('用户不存在');
            }
        }
        return View::fetch('login');
    }
    //注册逻辑编写
    public function register()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $validate = validate('User');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $data['password'] = md5($data['password']);
            $data['reg_time'] = time();
            $res = Db::name('users')->insert($data);
            if ($res) {
                $this->success('注册成功', 'index');
            } else {
                $this->error('注册失败');
            }
            return;

            
        }
        return View::fetch('register');
    }
    // 退出登录
    public function logout()
    {
        session('user', null);
        $this->success('退出成功', 'index');
    }

}
