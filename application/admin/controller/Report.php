<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Resource as ResourceModel; // 使用别名来避免冲突
use app\common\model\Report as ReportModel; // 导入 Report 模型


class Report extends Controller
{
    // 应用中间件
    protected $middleware = [
        'CheckLogin',
    ];
    /*
    'resource_id' => $id,
            'reason' => $reason, // 举报原因
            'details' => $details, // 举报详细信息
            'contact' => $contact, // 联系方式
            'time' => time(),
    */
    public function index(Request $request)
    {
        $keyword = $request->param('keyword', '');

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
        $report = ReportModel::get($id);
        if (!$report) {
            return $this->error('报告不存在');
        }

        $report->delete();

        return $this->success('删除成功');
    }

}