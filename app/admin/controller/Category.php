<?php
namespace app\admin\controller;
use think\facade\View;
use think\facade\Request;
use think\facade\Db;
use app\admin\model\Category as CategoryModel;


class Category
{
    use \liliuwei\think\Jump;
    protected $middleware = [
        \app\admin\middleware\Check::class,
    ];
    // 网盘分类
    public function index(){
        $keyword = input('keyword');

        $query = CategoryModel::where(function ($query) use ($keyword) {
            if ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
        })->order('id', 'desc');

        $list = $query->paginate(10, false, ['type' => 'bootstrap', 'query' => request()->param()]);
        return View::fetch('index',['data' => $list, 'keyword' => $keyword]);

    }
    public function add()
    {
        return View::fetch('add');
    }
    //删除网盘分类
    public function delete($id)
    {
        // 执行删除操作
        $result = CategoryModel::destroy($id);

        if ($result) {
            return json(['status' => 1, 'message' => '删除成功']);
        } else {
            return json(['status' => 0, 'message' => '删除失败']);
        }
    }
    public function edit($id)
    {
        $category = CategoryModel::find($id);
        return View::fetch('edit', ['category' => $category]);
    }
    public function save()
    {
        if (Request::isPost()) {
            $data = Request::post();
            $result = CategoryModel::create($data);
            if ($result) {
                return $this->success('添加成功');
            } else {
                return $this->error('添加失败');
            }
        }
    }
    public function update()
    {
        $id = Request::param('id');
        if (Request::isPost()) {
            $data = Request::post();
            $result = CategoryModel::update($data, ['id' => $id]);
            if ($result) {
                return $this->success('更新成功');
            } else {
                return $this->error('更新失败');
            }
        }
    }
}