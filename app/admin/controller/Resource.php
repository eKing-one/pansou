<?php
// app/admin/controller/Resource.php
namespace app\admin\controller;

use think\facade\View;
use think\facade\Request;
use think\facade\Db;
use app\admin\model\Resource as ResourceModel; // 使用别名来避免冲突
use PhpOffice\PhpSpreadsheet\IOFactory; // 引入 PhpSpreadsheet


class Resource
{
    use \liliuwei\think\Jump; // 引入Jump trait
    // 应用中间件
    protected $middleware = [
        \app\admin\middleware\Check::class,
    ];

    public function index()
    {
        $keyword = input('keyword');

        $query = ResourceModel::with('category')  // 使用 with 方法加载关联的分类信息
            ->where(function ($query) use ($keyword) {
                if ($keyword) {
                    $query->where('title', 'like', '%' . $keyword . '%');
                }
            })->order('id', 'desc');
        $list = $query->paginate(10, false, ['type' => 'bootstrap', 'query' => request()->param()]);

        return View::fetch('index', ['data' => $list, 'keyword' => $keyword]);
    }

    public function add()
    {
        //查询网分类
        $categories = Db::name('categories')->select();
        return View::fetch('add', ['categories' => $categories]);
    }
    public function edit($id)
    {
        $resource = ResourceModel::find($id);
        $categories = Db::name('categories')->select();
        if (!$resource) {
            return $this->error('资源不存在');
        }
        
        return view('edit', ['data' => $resource, 'categories' => $categories]);
    }
    // 批量导入
    public function import()
    {
        return View::fetch('import');
    }


    public function importData()
    {
        // 获取上传的文件
        $file = Request::file('file');
        if (!$file) {
            return $this->error('请选择上传文件');
        }

        // 验证文件类型
        $ext = $file->getOriginalExtension();
        if ($ext !== 'xlsx' && $ext !== 'xls') {
            return $this->error('不支持的文件类型');
        }

        // 保存文件
        $saveName = $file->move(root_path() . 'public/uploads');
        if (!$saveName) {
            return $this->error('文件保存失败');
        }

        // 获取文件路径
        $filePath = $saveName->getRealPath();

        // 创建Excel文件解析器
        $reader = IOFactory::createReaderForFile($filePath);
        // 加载Excel文件
        $spreadsheet = $reader->load($filePath);
        //读取sheet列表
        // 获取指定的工作表
        $sheet = $spreadsheet->getSheetByName('批量分享');
        if (!$sheet) {
            return $this->error('找不到指定的工作表');
        }
        // 将工作表转换为数组
        $rows = $sheet->toArray();

        if (empty($rows)) {
            return $this->error('导入数据为空');
        }

        // 移除标题行
        array_shift($rows);
        $successCount = 0;
        $failCount = 0;
        // var_dump($rows);
        // 遍历数组，导入数据到数据库
        foreach ($rows as $row) {
            if($ext == 'xls'){ //判断xls文件则默认xls文件，则pantool批量分享的文件
                if(empty($row['0']))continue;
                $title = $row[0]; // 链接名字
                $content = $row[1]; // 分享描述
                $url = $row[2]; // 分享链接
                $code = $row[3]; // 文件密码
                $categories = Db::name('categories')->where('name', $row[4])->find();
                $category_id = $categories ? $categories['id'] : 0;
                $size = $row[5]; // 文件大小
                $time = $row[6] ? $row[6] : date('Y-m-d H:i:s');
            }else{
                if(empty($row['1']))continue;
                $title = $row[1]; // 链接名字
                $content = $row[1]; // 分享描述
                $url = $row[3]; // 分享链接
                $code = $row[4]; // 文件密码
                $categories = Db::name('categories')->where('name', $row[5])->find();
                $category_id = $categories ? $categories['id'] : 0;
                $size = $row[12]; // 文件大小
                $time = date('Y-m-d H:i:s');
            }
            // 查询是否已存在相同的文件名或链接，如果存在则跳过
            $in_file = Db::name('resources')->where('title', $title)->find();
            if ($in_file) {
                $failCount++;
                continue;
            }
            $resource = new ResourceModel();
            $resource->title = $title; // 资源标题
            $resource->content = $content; // 资源描述
            $resource->url = $url;  // 资源链接
            $resource->code = $code; // 资源密码
            $resource->category_id = $category_id; // 资源分类
            $resource->size = $size; // 资源大小
            $resource->created_at = $time; // 资源创建时间

            if (!$resource->save()) {
                $failCount++;
            } else {
                $successCount++;
            }
        }

        // 删除临时文件
        unlink($filePath);

        // 返回操作结果
        return $this->success('导入成功，成功条数：' . $successCount . '，失败条数：' . $failCount);
    }

    public function save(Request $request)
    {
        $data = $request->only(['title', 'content', 'category_id', 'url', 'code']);
        $result = ResourceModel::create($data);
        if ($result) {
            return $this->success('添加成功', 'admin/resource/index');
        } else {
            return $this->error('添加失败');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['title', 'content', 'category_id', 'url', 'code']);
        $result = ResourceModel::update($data, ['id' => $id]);
        if ($result) {
            return $this->success('更新成功', 'admin/resource/index');
        } else {
            return $this->error('更新失败');
        }
    }

    public function delete($id)
    {
        $result = ResourceModel::destroy($id);
        if ($result) {
            return json(['status' => 1, 'message' => '删除成功']);
        } else {
            return json(['status' => 0, 'message' => '删除失败']);
        }
    }
}