<?php
use think\facade\Route;

// Route::get('search/:keyword','index/search/index')->validate([
//     'keyword' => 'require|regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\s]+$/u'
// ]);
Route::get('search/:keyword', 'Search/index');
Route::rule('resource/:id','Search/resources');
Route::rule('collect/:keyword','Api/updateHistory');

