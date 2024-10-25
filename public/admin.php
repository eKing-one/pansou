<?php

namespace think;

require __DIR__ . '/../vendor/autoload.php';
define('ROOT_PATH', dirname(__DIR__));
// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->name('admin')->run();

$response->send();

$http->end($response);
