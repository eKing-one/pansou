<?php
// app/admin/controller/Resource.php
namespace app\admin\controller;

use think\facade\View;
use think\Request;
use think\facade\Db;
use app\admin\model\Resource as ResourceModel; // 使用别名来避免冲突
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; // Excel文件解析器


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
    // 网盘分类
    public function category(){
        $keyword = input('keyword');

        $query = Db::name('categories')->where(function ($query) use ($keyword) {
            if ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%');
            }
        })->order('id', 'desc');

        $list = $query->paginate(10, false, ['type' => 'bootstrap', 'query' => request()->param()]);
        return View::fetch('category',['data' => $list, 'keyword' => $keyword]);

    }

    //批量导入数据处理
    public function importData()
    {
        $type = 2; // 获取type参数，默认为1
        $file = request()->file('file');
        if (!$file) {
            return $this->error('请选择文件');
        }

        $info = $file->move(ROOT_PATH . 'public/uploads');
        if (!$info) {
            return $this->error('文件上传失败');
        }

        //随机文件名
        $filePath = $info->getFilename();
        $filePath = ROOT_PATH . 'public/uploads/' . $filePath;

        $reader = new Xlsx(); // 创建Excel文件解析器

        $spreadsheet = $reader->load($filePath); // 加载Excel文件

        $sheet = $spreadsheet->getSheetByName('批量分享');
        $rows = $sheet->toArray(); // 将工作表转换为数组
        if (empty($rows)) {
            return $this->error('导入数据为空');
        }
        // 移除标题行
        array_shift($rows);
        $successCount = 0;
        $failCount = 0;


        // 遍历数组，导入数据到数据库
        foreach ($rows as $row) {
            // dump($row);
            $title = $row[1]; //链接名字
            $content = $row[2]; //分享描述
            $url = $row[3]; //分享链接
            $code = $row[4]; // 文件密码
            // 假设网盘名称存储在class字段，查询分类id
            $categories = Db::name('categories')->where('title', $row[5])->find();
            if ($categories) {
                $category_id = $categories['id'];
            } else {
                $category_id = 0;
            }
            $size = $row[12]; //文件大小
            // 查询是否已存在相同的文件名或链接有则跳过
            $in_file = Db::name('resources')->where('title', $title)->find();
            if ($in_file) {
                $failCount++;
                continue;
            }
            $resource = new ResourceModel();
            $resource->title = $title;
            $resource->content = $content;
            $resource->url = $url;
            $resource->code = $code;
            // 假设网盘名称存储在class字段
            $resource->category_id = $category_id;
            // 假设文件大小存储在size字段
            $resource->size = $size;
            $resource->time = date('Y-m-d H:i:s');

            if ($resource->save() === false) {
                $failCount++;
                // Log::record("保存失败: " . json_encode($resource->getError()), 'error');
            } else {
                $successCount++;
            }
            
        }

        unlink($filePath);

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
    public function regexpUrl($data)
    {
        // 百度匹配链接
        $reBaidu = '/http[s]?:\/\/pan\.baidu\.com\/s\/[\w-]+/';
        preg_match_all($reBaidu, $data, $baiduMatches, PREG_SET_ORDER);

        // 阿里匹配链接
        $reAli = '/http[s]?:\/\/[^pan]+aliyundrive\.com\/s\/[\w-]+/';
        preg_match_all($reAli, $data, $aliMatches, PREG_SET_ORDER);

        // 115匹配链接
        $re115 = '/(https:\/\/115\.com\/s\/[\w-]+)[?#]+/i';
        preg_match_all($re115, $data, $re115Matches, PREG_SET_ORDER);

        // 夸克匹配链接
        $reQuark = '/(https:\/\/pan\.quark\.cn\/s\/[\w-]+)/i';
        preg_match_all($reQuark, $data, $reQuarkMatches, PREG_SET_ORDER);

        // 提取码规则1 阿里
        $reAliPwd = '/(提取码: \w{4})\s?\n链接：\s*(http[s]?:\/\/[^pan]+aliyundrive\.com\/s\/[\w-]+)/i';
        preg_match_all($reAliPwd, $data, $aliPwdMatches, PREG_SET_ORDER);

        // 提取码规则2 百度
        $reBaiduPwd = '/(http[s]?:\/\/pan\.baidu\.com\/s\/[\w-]+)\s*(提取码:[\s]?\w{4})/i';
        preg_match_all($reBaiduPwd, $data, $baiduPwdMatches, PREG_SET_ORDER);

        // 115提取码规则1
        $re115Pwd = '/(https:\/\/115\.com\/s\/[\w-]+)#\r\n.+?\n访问码：\s*(.{4})/i';
        preg_match_all($re115Pwd, $data, $re115PwdMatches, PREG_SET_ORDER);

        // 115提取码规则2
        $re115Pwd2 = '/(https:\/\/115\.com\/s\/[\w-]+)[?#]+password=(.{4})/i';
        preg_match_all($re115Pwd2, $data, $re115Pwd2Matches, PREG_SET_ORDER);

        // 夸克提取码规则
        $reQuarkPwd = '/(https:\/\/pan\.quark\.cn\/s\/[\w-]+)\?passcode=(.{4})/i';
        preg_match_all($reQuarkPwd, $data, $reQuarkPwdMatches, PREG_SET_ORDER);
        return $data;
    }
}