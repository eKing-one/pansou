<?php
// app/admin/controller/Index.php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\User as UserModel;
use app\common\model\Resource as ResourceModel;
use app\common\model\Report as ReportModel;
// use app\common\model\HotKeyword as HotKeywordModel;
// use app\common\model\Order as OrderModel;
// use app\common\model\Activity as ActivityModel;

class Index extends Controller
{
    // 应用中间件
    protected $middleware = [
        'CheckLogin',
    ];

    public function index()
    {
        // 获取用户总数
        $userCount = UserModel::count();

        // 获取资源总数
        $resourcesCount = ResourceModel::count();

        // 举报总数（假设有一个举报表）
        $reportsCount = ReportModel::count(); // 如果有这个模型的话

        // 关键字总数（假设有一个搜索关键词表）
        // $hotKeywordsCount = HotKeywordModel::count(); // 如果有这个模型的话

        // // 获取订单总数
        // $orderCount = OrderModel::count();

        // // 获取今日访问量（假设有一个访问记录表）
        // $visitsToday = ActivityModel::whereTime('created_at', 'today')->count();

        // // 获取最新动态
        // $activities = ActivityModel::order('created_at', 'desc')->limit(5)->select();

        // 返回视图并传递数据
        return view('index', [
            'userCount' => $userCount,
            'resourcesCount' => $resourcesCount,
            'reportsCount' => $reportsCount,
            // 'ordersCount' => $orderCount,
            // 'visitsToday' => $visitsToday,
            // 'activities' => $activities,
        ]);
    }
}