# LinkChecker 使用说明
LinkChecker 是一个用于检查不同云存储服务分享链接有效性的工具。目前支持的云存储服务包括阿里云盘、夸克网盘、百度网盘和115网盘。

## 安装
要使用 LinkChecker，首先需要确保你的 PHP 环境已经安装并配置好 cURL 扩展。然后，你可以通过以下方式使用 LinkChecker：

### 直接下载源代码
将 LinkChecker 类文件下载到你的项目中，并包含到你的 PHP 脚本中：


```require_once 'path/to/LinkChecker.php';```
### 使用 Composer
LinkChecker 没有发布到了 Composer 仓库，你可以修改 Composer.json 进行安装：
```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/eKing-one/netdisk-link-checker.git"
        }
    ],
    "require": {
        "eking/netdisk": "dev-main"
    }
}
```
```
composer update eking/netdisk
```

然后，在你的 PHP 脚本中使用 Composer 的自动加载功能：

```
require_once 'vendor/autoload.php';
```

## 使用方法
以下是如何使用 LinkChecker 检查不同云存储服务分享链接有效性的示例：

```
<?php
require_once 'path/to/LinkChecker.php';

use eking\netdisk\LinkChecker;

// 创建 LinkChecker 实例
$linkChecker = new LinkChecker();

// 检查阿里云盘分享链接是否有效
$aliYunUrl = 'https://www.aliyundrive.com/s/某某某某某某某某';
if ($linkChecker->checkUrl($aliYunUrl)) {
    echo "阿里云盘链接有效\n";
} else {
    echo "阿里云盘链接无效\n";
}

// 检查夸克网盘分享链接是否有效
$quarkUrl = 'https://pan.quark.cn/s/某某某某某某某某';
if ($linkChecker->checkUrl($quarkUrl)) {
    echo "夸克网盘链接有效\n";
} else {
    echo "夸克网盘链接无效\n";
}

// 检查百度网盘分享链接是否有效
$baiduYunUrl = 'https://pan.baidu.com/某某某某某某某某';
if ($linkChecker->checkUrl($baiduYunUrl)) {
    echo "百度网盘链接有效\n";
} else {
    echo "百度网盘链接无效\n";
}

// 检查115网盘分享链接是否有效
$d115Url = 'https://115.com/某某某某某某某某';
if ($linkChecker->checkUrl($d115Url)) {
    echo "115网盘链接有效\n";
} else {
    echo "115网盘链接无效\n";
}
```
## 支持的云存储服务
阿里云盘
夸克网盘
百度网盘
115网盘
## 注意事项
LinkChecker 只能检查分享链接的有效性，并不能保证链接指向的文件或文件夹存在。
LinkChecker 需要 PHP 的 cURL 扩展支持。
## 贡献
如果你希望为 LinkChecker 添加更多云存储服务的支持，或者修复 bug，欢迎提交 Pull Request。

## 版权

© [eKing], 2024.
