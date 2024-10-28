# PHP网盘搜索引擎

## 简介

      这是一个基于Thinkphp 6.1 + MySQL开发的网盘搜索引擎，可以批量导入各大网盘链接，例如百度网盘、阿里云盘、夸克网盘等。


## 演示地址

 - https://www.1620.top

## 更新记录

- 2024年10月29日：
   - 增加后台登陆和投诉的验证码功能。
   - 美化后台登陆界面。
   - 修复批量导入时间字段不正确，增加批量导入的时间字段。

- 2024年10月25日：
   - 使用thinkphp6.0框架重构，优化代码结构，增加功能，优化性能。
   - 增加后台网盘分类功能。
   - 修复排序功能。
   - 修复诸多bug。
   - 移除检测网盘时效的api接口，改为本地检测。
   - 增加后台网盘分类和前台显示功能。
   - 修复批量导入，增加导入模板。


## 功能特点

- **网盘失效检测**
- **网盘链接管理**
- **热搜词管理**
- **批量上传网盘链接**
- **搜索结果排序**
- **网盘分类管理功能**
- **等其他功能正在更新中。。**

## 系统要求

- PHP 7.4 或更高版本
- MySQL 5.7 或更高版本（或其他兼容的数据库）
- Apache 或 Nginx 作为Web服务器

## 安装指南

1. **下载源码**：

   - 从GitHub下载最新版本的源码。

2. **上传文件**：

   - 将源码上传到服务器的Web目录。

3. **配置运行目录**：

   - 将/public设置为网站运行目录。
   - 使用thinkphp伪静态规则。
   
4. **数据库配置**：

  - 在`.env`文件中配置数据库连接信息，线上运行记得把APP_DEBUG和DEBUG改为false。
  
  - 导入`install.sql`到数据库。

5. **访问系统**：

   - 后台地址：`http://yourdomain.com/admin.php`
   - 默认账号密码：admin/123456

## 功能建议

- 提交到[Issues](https://github.com/eKing-one/pansou/issues)或发邮件admin[AT]eking.one


## 免责声明

      本程序仅供学习了解。使用本程序必循遵守部署服务器所在地、所在国家和用户所在国家的法律法规, 程序作者不对使用者任何不当行为负责。