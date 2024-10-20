<?php
// app/admin/controller/User.php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\User as UserModel;
class User extends Controller
{
    // 应用中间件
    protected $middleware = [
        'CheckLogin',
    ];
    
    public function index(Request $request)
    {
        $keyword = $request->param('keyword', '');

        // 创建一个查询对象
        $query = UserModel::where(function ($query) use ($keyword) {
            if ($keyword) {
                // 使用闭包函数来构建复杂的查询条件
                $query->where('username', 'like', '%' . $keyword . '%');
            }
        });

        // 分页查询
        $list = $query->paginate(10, false, ['type' => 'bootstrap', 'query' => request()->param()]);

        return view('index', ['list' => $list, 'keyword' => $keyword]);
    }

    public function add()
    {
        return view('add');
    }

    public function save(Request $request)
    {
        // 获取请求中的数据
        $data = $request->only(['username', 'email', 'password']);

        // 对密码进行哈希处理
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // 创建新用户
        $result = UserModel::create($data);

        if ($result) {
            return $this->success('添加成功', 'admin/user/index');
        } else {
            return $this->error('添加失败');
        }
    }

    public function edit($id)
    {
        $user = UserModel::get($id);
        return view('edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['username', 'email', 'password']);

        // 对密码进行哈希处理
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $result = UserModel::update($data, ['id' => $id]);
        if ($result) {
            return $this->success('更新成功', 'admin/user/index');
        } else {
            return $this->error('更新失败');
        }
    }

    public function delete($id)
    {
        $result = UserModel::destroy($id);
        if ($result) {
            return json(['status' => 1, 'message' => '删除成功']);
        } else {
            return json(['status' => 0, 'message' => '删除失败']);
        }
    }
}