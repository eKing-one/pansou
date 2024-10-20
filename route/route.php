<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('search/:keyword','index/Search/index')->validate([
    'keyword' => 'require|regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\s]+$/u'
]);
Route::rule('resource/:id','index/Search/resources');
Route::rule('collect/:keyword','index/updateHistory');

// 登录路由不需要登录检查
Route::get('admin/login', 'admin/Login/index');
Route::post('admin/login', 'admin/Login/login');
Route::get('admin/logout', 'admin/Login/logout');

return [

];
