<?php
// app/admin/controller/Index.php
namespace app\admin\controller;

use think\facade\View;

use app\admin\model\User as UserModel;
use app\admin\model\Resource as ResourceModel;
use app\admin\model\Report as ReportModel;
use app\admin\model\Keyword as KeywordModel;
class Index
{
    // 应用中间件
    protected $middleware = [
        \app\admin\middleware\Check::class,
    ];

    public function index()
    {
        // 获取用户总数
        $userCount = UserModel::count();

        // 获取资源总数
        $resourcesCount = ResourceModel::count();

        // 举报总数（假设有一个举报表）
        $reportsCount = ReportModel::count(); // 如果有这个模型的话

        // 热门关键词总数（假设有一个搜索关键词表）
        $hotKeywordsCount = KeywordModel::count(); // 如果有这个模型的话

        // // 获取订单总数
        // $orderCount = OrderModel::count();

        // // 获取今日访问量（假设有一个访问记录表）
        // $visitsToday = ActivityModel::whereTime('created_at', 'today')->count();

        // // 获取最新动态
        // $activities = ActivityModel::order('created_at', 'desc')->limit(5)->select();
        // 系统信息
        $systemInfo = [
            'os' => PHP_OS,
            'php' => PHP_VERSION,
            'server' => $_SERVER['SERVER_SOFTWARE'],
            'thinkphp' => app()->version(),
        ];
        // 返回视图并传递数据
        return View::fetch('index', [
            'userCount' => $userCount,
            'resourcesCount' => $resourcesCount,
            'reportsCount' => $reportsCount,
            'hotKeywordsCount' => $hotKeywordsCount,
            'systemInfo' => $systemInfo
        ]);
    }

}