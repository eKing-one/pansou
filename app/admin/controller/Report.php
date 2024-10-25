<?php
namespace app\admin\controller;

use think\facade\View;

use app\admin\model\Resource as ResourceModel; // 使用别名来避免冲突
use app\admin\model\Report as ReportModel; // 导入 Report 模型


class Report
{
    use \liliuwei\think\Jump; // 引入 Jump 类
    // 应用中间件
    protected $middleware = [
        \app\admin\middleware\Check::class,
    ];
    /*
    'resource_id' => $id,
            'reason' => $reason, // 举报原因
            'details' => $details, // 举报详细信息
            'contact' => $contact, // 联系方式
            'time' => time(),
    */
    public function index()
    {
        $keyword = input('keyword');

        $query = ReportModel::where(function($query) use ($keyword) {
            if ($keyword) {
                $query->where('resource_id', 'like', '%'. $keyword. '%');
            }
        })->order('id', 'desc');

        $list = $query->paginate(10, false, ['type' => 'bootstrap', 'query' => request()->param()]);

        return view('index', ['data' => $list, 'keyword' => $keyword]);
    }
    public function delete($id)
    {
        $report = ReportModel::find($id);
        if (!$report) {
            return $this->error('报告不存在');
        }

        $report->delete();

        return $this->success('删除成功');
    }

}