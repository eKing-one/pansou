<?php
// app/admin/controller/Keyword.php
namespace app\admin\controller;

use think\facade\View;
use think\Request;
use app\admin\model\Keyword as KeywordModel;

class Keyword
{
    use \liliuwei\think\Jump; // 引入Jump trait
    // 应用中间件
    protected $middleware = [
        \app\admin\middleware\Check::class,
    ];
    
    public function index()
    {
        $keyword = input('keyword');

        // 创建一个查询对象
        $query = KeywordModel::where(function ($query) use ($keyword) {
            if ($keyword) {
                // 使用闭包函数来构建复杂的查询条件
                $query->where('keyword', 'like', '%' . $keyword . '%');
            }
        });

        // 分页查询
        $list = $query->paginate(10, false, ['type' => 'bootstrap', 'query' => request()->param()]);

        return View::fetch('index', ['list' => $list, 'keyword' => $keyword]);
    }

    public function add()
    {
        return View::fetch('add');
    }

    public function save(Request $request)
    {
        // 获取请求中的数据
        $data = $request->only(['keyword', 'search_count','is_audit']);

        $result = KeywordModel::create($data);

        if ($result) {
            return $this->success('添加成功', 'admin/keyword/index');
        } else {
            return $this->error('添加失败');
        }
    }

    public function edit($id)
    {
        $keyword = KeywordModel::find($id);
        return view('edit', ['keyword' => $keyword]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['keyword', 'search_count','is_audit']);

        $result = KeywordModel::update($data, ['id' => $id]);
        if ($result) {
            return $this->success('更新成功', 'admin/keyword/index');
        } else {
            return $this->error('更新失败');
        }
    }

    public function delete($id)
    {
        $result = KeywordModel::destroy($id);
        if ($result) {
            return json(['status' => 1, 'message' => '删除成功']);
        } else {
            return json(['status' => 0, 'message' => '删除失败']);
        }
    }
}