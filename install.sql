SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`id`, `name`, `url`, `created_at`, `updated_at`) VALUES
(1, '阿里云盘', 'https://www.aliyundrive.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(2, '夸克云盘', 'https://www.quarkcloud.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(3, '天翼云盘', 'https://www.189.cn/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(4, '腾讯微云', 'https://www.weiyun.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(5, '百度网盘', 'https://pan.baidu.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(6, '中国移动网盘', 'https://www.chinamobile.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(7, '华为云空间', 'https://www.huaweicloud.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(8, '360安全云盘', 'https://yun.360.cn/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(9, '亿方云', 'https://www.fangcloud.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(10, '坚果云', 'https://www.jianguoyun.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(11, '飞猫盘', 'https://www.feimaopian.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(12, '123云盘', 'https://www.123pan.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(13, '曲奇云盘', 'https://www.quqiyun.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(14, 'huang1111网盘', 'https://www.huang1111.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(15, '钉盘', 'https://www.dingtalk.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(16, '蓝奏云', 'https://www.lanzou.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(17, '迅雷云盘', 'https://pan.xunlei.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(18, '小米云盘', 'https://i.mi.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(19, '联通云盘', 'https://www.wo.com.cn/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(20, '城通网盘', 'https://www.ctfile.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(21, '超星网盘', 'https://www.chaoxing.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:28'),
(22, 'UC网盘', 'https://www.vivo.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:54'),
(23, 'OPPO云盘', 'https://www.oppo.com/', '2024-10-22 06:28:28', '2024-10-22 06:28:59');

CREATE TABLE `config` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '配置名称',
  `value` text NOT NULL COMMENT '配置值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `config` (`id`, `name`, `value`) VALUES
(1, 'site_title', '多多搜盘'),
(2, 'site_description', '专业提供网盘资源搜索的网站，全网资源实时更新，包括教程、电影、剧集、图片、综艺、音乐、图书、软件、动漫、游戏等各类资源应有尽有。'),
(3, 'site_keywords', '网盘搜索,网盘搜索引擎,百度云搜索,百度云资源,百度网盘,网盘百度,云盘搜索,网盘下载'),
(4, 'icp_number', '京ICP备12345678号-1'),
(5, 'contact_info', '电话: 123-456-7890'),
(6, 'email_address', 'contact@example.com'),
(7, 'wechat_qrcode_url', '/path/to/wechat-qrcode.png'),
(8, 'site_logo_url', '/static/img/logo.png'),
(9, 'footer_copyright', '版权所有 © 2024 多多搜盘'),
(10, 'maintenance_mode', '0');

CREATE TABLE `keywords` (
  `id` int(11) NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `search_count` int(11) DEFAULT '1',
  `is_audit` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `keywords` (`id`, `keyword`, `search_count`, `is_audit`, `created_at`) VALUES
(98, 'C语言', 10, 1, '2024-10-22 11:11:22'),
(99, 'Word', 8, 1, '2024-10-22 11:56:43'),
(100, '字体', 4, 1, '2024-10-22 11:46:42'),
(101, 'Python入门', 2, 0, '2024-09-28 03:46:12'),
(102, '创客学院', 1, 0, '2024-09-28 03:42:24'),
(103, '海贼王RED', 1, 0, '2024-09-28 03:42:24'),
(104, '公务员', 1, 0, '2024-09-28 03:42:24'),
(105, '嵌入式', 1, 0, '2024-09-28 03:42:24'),
(106, '美女', 1, 0, '2024-09-28 03:42:24'),
(107, '英语的平行世界', 1, 0, '2024-09-28 03:42:24'),
(108, '初中物理', 1, 0, '2024-09-28 03:42:24'),
(109, '宏观经济学', 1, 0, '2024-09-28 03:42:24'),
(110, '音乐', 1, 0, '2024-09-28 03:42:24'),
(111, '高考', 1, 0, '2024-09-28 03:42:24'),
(112, 'Excel', 1, 0, '2024-09-28 03:42:24'),
(113, '自动控制', 1, 0, '2024-09-28 03:42:24'),
(114, '一建', 1, 0, '2024-09-28 03:42:24'),
(115, '考研数学', 1, 0, '2024-09-28 03:42:24'),
(116, '外刊', 1, 0, '2024-09-28 03:42:24'),
(117, '纪录片', 2, 0, '2024-09-28 03:46:16'),
(118, 'VAM', 1, 0, '2024-09-28 03:42:24'),
(119, '海贼王', 1, 0, '2024-09-28 03:42:24'),
(120, '黄帝内经', 1, 0, '2024-09-28 03:42:24'),
(121, '税务师', 1, 0, '2024-09-28 03:42:24'),
(122, 'PS', 1, 0, '2024-09-28 03:42:24'),
(123, 'CAD', 1, 0, '2024-09-28 03:42:24'),
(124, 'Adobe', 1, 0, '2024-09-28 03:42:24'),
(125, '漫威', 1, 0, '2024-09-28 03:42:24'),
(126, '扬名立万', 1, 0, '2024-09-28 03:42:24'),
(127, '小学英语', 1, 0, '2024-09-28 03:42:24'),
(128, '初中英语', 1, 0, '2024-09-28 03:42:24'),
(129, '教资真题', 1, 0, '2024-09-28 03:42:24'),
(130, '写真', 1, 0, '2024-09-28 03:42:24'),
(131, '新概念', 1, 0, '2024-09-28 03:42:24'),
(132, '军棋', 1, 0, '2024-09-28 03:42:24'),
(133, '系统分析师', 1, 0, '2024-09-28 03:42:24'),
(134, '迅雷', 1, 0, '2024-09-28 03:42:24'),
(135, '绘本故事', 1, 0, '2024-09-28 03:42:24'),
(136, '教招', 1, 0, '2024-09-28 03:42:24'),
(137, '书籍高中', 1, 0, '2024-09-28 03:42:24'),
(138, '考研英语', 1, 0, '2024-09-28 03:42:24'),
(139, '沪江', 1, 0, '2024-09-28 03:42:24'),
(141, '注册会计师', 1, 0, '2024-09-28 03:42:24'),
(142, '运维', 1, 0, '2024-09-28 03:42:24'),
(143, '江鸣百技斩', 1, 0, '2024-09-28 03:42:24'),
(144, '英语语法', 1, 0, '2024-09-28 03:42:24'),
(145, '法考', 1, 0, '2024-09-28 03:42:24'),
(146, '英语口语', 1, 0, '2024-09-28 03:42:24'),
(147, '传热学', 1, 0, '2024-09-28 03:42:24'),
(148, '雨中冒险', 1, 0, '2024-09-28 03:42:24'),
(149, 'PS教程', 1, 0, '2024-09-28 03:42:24'),
(150, '安全培训', 1, 0, '2024-09-28 03:42:24'),
(151, '西方经济学', 1, 0, '2024-09-28 03:42:24'),
(152, 'C4D', 1, 0, '2024-09-28 03:42:24'),
(153, '语法', 1, 0, '2024-09-28 03:42:44'),
(154, 'Python', 2, 0, '2024-09-28 03:46:32'),
(155, '系统分析师', 1, 0, '2024-09-28 03:42:44'),
(156, '步非烟', 1, 0, '2024-09-28 03:42:44'),
(157, 'Java', 1, 0, '2024-09-28 03:42:44'),
(158, 'Python', 2, 0, '2024-09-28 03:46:32'),
(159, '教资', 1, 0, '2024-09-28 03:42:44'),
(160, '三体', 1, 0, '2024-09-28 03:42:44'),
(161, '电影', 1, 0, '2024-09-28 03:42:44'),
(162, '专升本', 1, 0, '2024-09-28 03:42:44'),
(163, '初中数学', 1, 0, '2024-09-28 03:42:44'),
(164, '小说', 1, 0, '2024-09-28 03:42:44'),
(165, 'PPT模板', 1, 0, '2024-09-28 03:42:44'),
(166, '步非烟', 1, 0, '2024-09-28 03:42:44'),
(167, '杨亮', 1, 0, '2024-09-28 03:42:44'),
(168, '乐乐课堂', 1, 0, '2024-09-28 03:42:44'),
(169, '日语', 1, 0, '2024-09-28 03:42:44'),
(170, 'C语言', 10, 0, '2024-10-22 11:11:22'),
(171, 'Word', 8, 0, '2024-10-22 11:56:43'),
(172, '字体', 4, 0, '2024-10-22 11:46:42'),
(173, 'Python入门', 2, 0, '2024-09-28 03:46:12'),
(174, '创客学院', 1, 0, '2024-09-28 03:42:44'),
(175, '海贼王RED', 1, 0, '2024-09-28 03:42:44'),
(176, '公务员', 1, 0, '2024-09-28 03:42:44'),
(177, '嵌入式', 1, 0, '2024-09-28 03:42:44'),
(178, '美女', 1, 0, '2024-09-28 03:42:44'),
(179, '英语的平行世界', 1, 0, '2024-09-28 03:42:44'),
(180, '初中物理', 1, 0, '2024-09-28 03:42:44'),
(181, '宏观经济学', 1, 0, '2024-09-28 03:42:44'),
(182, '音乐', 1, 0, '2024-09-28 03:42:44'),
(183, '高考', 1, 0, '2024-09-28 03:42:44'),
(184, 'Excel', 1, 0, '2024-09-28 03:42:44'),
(185, '自动控制', 1, 0, '2024-09-28 03:42:44'),
(186, '一建', 1, 0, '2024-09-28 03:42:44'),
(187, '考研数学', 1, 0, '2024-09-28 03:42:44'),
(188, '外刊', 1, 0, '2024-09-28 03:42:44'),
(189, '纪录片', 2, 0, '2024-09-28 03:46:16'),
(190, 'VAM', 1, 0, '2024-09-28 03:42:44'),
(191, '海贼王', 1, 0, '2024-09-28 03:42:44'),
(192, '黄帝内经', 1, 0, '2024-09-28 03:42:44'),
(193, '税务师', 1, 0, '2024-09-28 03:42:44'),
(194, 'PS', 1, 0, '2024-09-28 03:42:44'),
(195, 'CAD', 1, 0, '2024-09-28 03:42:44'),
(196, 'Adobe', 1, 0, '2024-09-28 03:42:44'),
(197, '漫威', 1, 0, '2024-09-28 03:42:44'),
(198, '扬名立万', 1, 0, '2024-09-28 03:42:44'),
(199, '小学英语', 1, 0, '2024-09-28 03:42:44'),
(200, '初中英语', 1, 0, '2024-09-28 03:42:44'),
(201, '教资真题', 1, 0, '2024-09-28 03:42:44'),
(202, '写真', 1, 0, '2024-09-28 03:42:44'),
(203, '新概念', 1, 0, '2024-09-28 03:42:44'),
(204, '军棋', 1, 0, '2024-09-28 03:42:44'),
(205, '系统分析师', 1, 0, '2024-09-28 03:42:44'),
(206, '迅雷', 1, 0, '2024-09-28 03:42:44'),
(207, '绘本故事', 1, 0, '2024-09-28 03:42:44'),
(208, '教招', 1, 0, '2024-09-28 03:42:44'),
(209, '书籍高中', 1, 0, '2024-09-28 03:42:44'),
(210, '考研英语', 1, 0, '2024-09-28 03:42:44'),
(211, '沪江', 1, 0, '2024-09-28 03:42:44'),
(213, '注册会计师', 1, 0, '2024-09-28 03:42:44'),
(214, '运维', 1, 0, '2024-09-28 03:42:44'),
(215, '江鸣百技斩', 1, 0, '2024-09-28 03:42:44'),
(216, '英语语法', 1, 0, '2024-09-28 03:42:44'),
(217, '法考', 1, 0, '2024-09-28 03:42:44'),
(218, '英语口语', 1, 0, '2024-09-28 03:42:44'),
(219, '传热学', 1, 0, '2024-09-28 03:42:44'),
(220, '雨中冒险', 1, 0, '2024-09-28 03:42:44'),
(221, 'PS教程', 1, 0, '2024-09-28 03:42:44'),
(222, '安全培训', 1, 0, '2024-09-28 03:42:44'),
(223, '西方经济学', 1, 0, '2024-09-28 03:42:44'),
(224, 'C4D', 1, 0, '2024-09-28 03:42:44'),
(225, 'checkUrl', 1, 0, '2024-10-20 11:14:07'),
(226, '213123', 2, 0, '2024-10-20 11:35:54'),
(227, '1', 1, 0, '2024-10-22 11:16:40'),
(228, '333', 1, 0, '2024-10-22 11:20:43'),
(229, '111', 1, 0, '2024-10-22 11:47:26');

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `resources` (
  `id` int(15) UNSIGNED NOT NULL,
  `title` varchar(2555) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '简介',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(40) DEFAULT '0' COMMENT '网盘分类',
  `label` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL COMMENT '上传时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(3, 'admin', 'admin@eking.one', '$2y$10$paDvePpALzpppmSKUj.oJOaIi5ktxaLnsvIJ9rQxQSrrT7tktw8du', 1727187134);


ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `keywords`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resource_id` (`resource_id`);

ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`) USING BTREE;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

ALTER TABLE `config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `keywords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `resources`
  MODIFY `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

