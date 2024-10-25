<?php
// use think\facade\Route;

// 登录路由不需要登录检查
// Route::get('login', 'admin/Login/index');
// Route::post('login', 'admin/Login/login');
// Route::get('logout', 'admin/Login/logout');

// application/admin/route.php
use think\facade\Route;
Route::get('dashboard', 'index/dashboard');

Route::get('login', 'login/index');
Route::post('login', 'login/login');
Route::get('logout', 'login/logout');

