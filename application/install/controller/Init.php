<?php


namespace app\install\controller;


use think\Controller;
use think\Db;
use think\facade\Session;
use think\Request;
use think\Exception;

class Init extends  Controller
{
    /**第二步
     */
    public function step2()
    {
        //检测，上一步是否已配置
        if (Session::get('db_info.ifconfig') == 'true') {
            return $this->fetch('install/init');
        } else {
            //重定向，错误哦
            $this->error('错误：请先完成上一步骤的设置！');
        }
    }

    /** 判断登录密码是否符合规范，并存入session
     * @param Request $request ($username, $password)
     * @return string
     */
    public function checkPasswd(Request $request)
    {
        //获得用户名，密码
        $username = input('username');
        $password = input('password');

        //用户名长度限制
        if (strlen($username) > 15 || strlen($username) < 3) {
            return '用户名长度必须在3-15之间';
        }
        //密码长度限制
        if (strlen($password) > 15 || strlen($password) < 5) {
            return '密码长度必须在5-15之间';
        }
        //防止敏感字符，.除外
        if (preg_match("/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\/|\;|\'|\`|\-|\=|\\\|\|/", $username . $password)) {
            return '错误：请勿使用非法符号，只允许使用数字，英文，和句号!';
        }
        //必须同时有。。。
        if (!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)/', $password)) {
            return '密码必须同时有数字和字母';
        }
        //全部符合
        $login_passwd = [
            'username' => $username,
            'password'   => $password
        ];
        //存入session
        Session::set('login_passwd', $login_passwd);
        return '成功';
    }

    /**
     * 建立数据库，1）执行sql文件 2）更改config/database.php中的内容
     * @return mixed
     */
    public function buildSql()
    {
        //获得在session中的数据，但是禁用cookie可能会有麻烦
        $username = Session::get('login_passwd.username');
        $password = Session::get('login_passwd.password');
        //加密
        $password = password_hash($password, PASSWORD_DEFAULT);
        // 检测连接
        try {
            $db_msg = [
                'type' => 'mysql',
                'hostname' => Session::get('db_info.hostname'),
                'database' => Session::get('db_info.database'),
                'username' => Session::get('db_info.username'),
                'password' => Session::get('db_info.passwd'),
                'hostport' => Session::get('db_info.hostport'),
                'charset' => 'utf8',
            ];
            //看是否能执行
            $db_conn = Db::connect($db_msg)
                ->query('show databases');

            //如果能
            if ($db_conn) {
                //执行sql语句
                //创建表
                // 删除并重新创建用户表
                Db::connect($db_msg)->execute('DROP TABLE IF EXISTS `users`;');
                Db::connect($db_msg)->execute('CREATE TABLE `users` (`id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT, `username` VARCHAR(40) COLLATE utf8mb4_unicode_ci NOT NULL, `password` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // 插入管理员登录信息
                Db::connect($db_msg)->execute("INSERT INTO users(`username`, `password`) VALUES('" . $username . "', '" . $password . "')");

                // 创建博客资源表
                Db::connect($db_msg)->execute('DROP TABLE IF EXISTS `resources`;');
                Db::connect($db_msg)->execute("CREATE TABLE `resources` (`id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT, `content` VARCHAR(2555) COLLATE utf8mb4_unicode_ci, `title` VARCHAR(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '文章分类', `url` VARCHAR(2555) COLLATE utf8mb4_unicode_ci COMMENT '网盘链接', `code` VARCHAR(255) COLLATE utf8mb4_unicode_ci COMMENT '提取码',`size` VARCHAR(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '内容大小', `time` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '上传时间', `class` VARCHAR(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '文章分类', `label` VARCHAR(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // 创建回收站表
                Db::connect($db_msg)->execute('DROP TABLE IF EXISTS `recycler`;');
                Db::connect($db_msg)->execute("CREATE TABLE `recycler` (`id` INT(15) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, `title` VARCHAR(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `content` LONGTEXT COLLATE utf8mb4_unicode_ci, `code` LONGTEXT COLLATE utf8mb4_unicode_ci, `time` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP, `class` VARCHAR(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `label` VARCHAR(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // 创建搜索历史表
                Db::connect($db_msg)->execute('DROP TABLE IF EXISTS `keywords`;');
                Db::connect($db_msg)->execute('CREATE TABLE `keywords` (`id` INT AUTO_INCREMENT PRIMARY KEY, `keyword` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `search_count` INT DEFAULT 1, `is_audit` INT DEFAULT 0, `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

                // 插入数据
                Db::connect($db_msg)->execute("INSERT INTO keywords (keyword, is_audit) VALUES
('语法', 1),
('Python', 1),
('系统分析师', 1),
('步非烟', 1),
('Java', 1),
('Python', 1),
('教资', 1),
('三体', 1),
('电影', 1),
('专升本', 1),
('初中数学', 1),
('小说', 1),
('PPT模板', 1),
('步非烟', 1),
('杨亮', 1),
('乐乐课堂', 1),
('日语', 1),
('C语言', 1),
('Word', 1),
('字体', 1),
('Python入门', 1),
('创客学院', 1),
('海贼王RED', 1),
('公务员', 1),
('嵌入式', 1),
('美女', 1),
('英语的平行世界', 1),
('初中物理', 1),
('宏观经济学', 1),
('音乐', 1),
('高考', 1),
('Excel', 1),
('自动控制', 1),
('一建', 1),
('考研数学', 1),
('外刊', 1),
('纪录片', 1),
('VAM', 1),
('海贼王', 1),
('黄帝内经', 1),
('税务师', 1),
('PS', 1),
('CAD', 1),
('Adobe', 1),
('漫威', 1),
('扬名立万', 1),
('小学英语', 1),
('初中英语', 1),
('教资真题', 1),
('写真', 1),
('新概念', 1),
('军棋', 1),
('系统分析师', 1),
('迅雷', 1),
('绘本故事', 1),
('教招', 1),
('书籍高中', 1),
('考研英语', 1),
('沪江', 1),
('国家玮', 1),
('注册会计师', 1),
('运维', 1),
('江鸣百技斩', 1),
('英语语法', 1),
('法考', 1),
('英语口语', 1),
('传热学', 1),
('雨中冒险', 1),
('PS教程', 1),
('安全培训', 1),
('西方经济学', 1),
('C4D', 1);");

                // 创建配置表
                Db::connect($db_msg)->execute('DROP TABLE IF EXISTS `config`;');
                Db::connect($db_msg)->execute("CREATE TABLE `config` (`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '配置名称', `value` TEXT NOT NULL COMMENT '配置值', PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                DB::connect($db_msg)->execute("INSERT INTO `config` (`id`, `name`, `value`) VALUES
(1, 'site_title', '多多搜盘'),
(2, 'site_description', '专业提供网盘资源搜索的网站，全网资源实时更新，包括教程、电影、剧集、图片、综艺、音乐、图书、软件、动漫、游戏等各类资源应有尽有。'),
(3, 'site_keywords', '网盘搜索,网盘搜索引擎,百度云搜索,百度云资源,百度网盘,网盘百度,云盘搜索,网盘下载'),
(4, 'icp_number', '京ICP备12345678号-1'),
(5, 'contact_info', '电话: 123-456-7890'),
(6, 'email_address', 'contact@example.com'),
(7, 'wechat_qrcode_url', '/path/to/wechat-qrcode.png'),
(8, 'site_logo_url', '/static/img/logo.png'),
(9, 'footer_copyright', '版权所有 © 2024 多多搜盘'),
(10, 'maintenance_mode', '0');");
                Db::connect($db_msg)->execute('DROP TABLE IF EXISTS `report`;');
                Db::connect($db_msg)->execute('CREATE TABLE `report` (`id` INT(11) NOT NULL, `resource_id` INT(11) NOT NULL, `reason` VARCHAR(255) NOT NULL, `details` TEXT NOT NULL, `contact` VARCHAR(255) DEFAULT NULL, `time` INT(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');

                //这是将之前生成好的数据写入 config/database.php
                $config = "<?php
                return [
                    // 数据库类型
                    'type'            => 'mysql',
                    // 服务器地址
                    'hostname'        => '" . Session::get('db_info.hostname') . "',
                    // 数据库名
                    'database'        => '" . Session::get('db_info.database') . "',
                    // 用户名
                    'username'        => '" . Session::get('db_info.username') . "',
                    // 密码
                    'password'        => '" . Session::get('db_info.passwd') . "',
                    // 端口
                    'hostport'        => '" . Session::get('db_info.hostport') . "',
                    // 连接dsn
                    'dsn'             => '',
                    // 数据库连接参数
                    'params'          => [],
                    // 数据库编码默认采用utf8
                    'charset'         => 'utf8',
                    // 数据库表前缀
                    'prefix'          => '',
                    // 数据库调试模式
                    'debug'           => true,
                    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
                    'deploy'          => 0,
                    // 数据库读写是否分离 主从式有效
                    'rw_separate'     => false,
                    // 读写分离后 主服务器数量
                    'master_num'      => 1,
                    // 指定从服务器序号
                    'slave_no'        => '',
                    // 自动读取主库数据
                    'read_master'     => false,
                    // 是否严格检查字段是否存在
                    'fields_strict'   => true,
                    // 数据集返回类型
                    'resultset_type'  => 'array',
                    // 自动写入时间戳字段
                    'auto_timestamp'  => false,
                    // 时间字段取出后的默认时间格式
                    'datetime_format' => 'Y-m-d H:i:s',
                    // 是否需要进行SQL性能分析
                    'sql_explain'     => true,
                    // Builder类
                    'builder'         => '',
                    // Query类
                    'query'           => '\\think\\db\\Query',
                    // 是否需要断线重连
                    'break_reconnect' => true,
                    // 断线标识字符串
                    'break_match_str' => [],
                ];
                ";
                $db_info = file_put_contents('../config/database.php', $config);
                //把公共文件中的数值调对，防止访问路由后重置

                // //向配置文件中，设置ifinit来判断是否初始化
                // $xml=simplexml_load_file("../common/common.xml");
                // $xml->ifinit = iconv('ISO-8859-1','utf-8','true');
                // $xml->saveXML('../common/common.xml');
                //做一堆检验看是否成功
                //
                //把session清了
                Session::clear();
                //
                return $this->fetch('install/toindex');
                //return '成功连接';
            }
        } catch (\Exception $e) {
            return json($e->getMessage());
            //先跳过去
            //            return $this->fetch('install/toindex');

        }
    }
}
