<?php

namespace app\index\controller;

use think\facade\View;
use think\facade\Db;
use app\admin\model\Resource as ResourceModel;

class Search
{


    use \liliuwei\think\Jump; // 引入Jump trait

    public function _empty()
    {
        return redirect('/');

    }
    private function buildTimeCondition($query, $sort)
    {
        // 根据sort参数设置时间查询条件
        switch ($sort) {
            case '1': //全部数据
                // $query->where('created_at');
                break;
            case '2':
                $query->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 month')));
                break;
            case '3':
                $query->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-6 months')));
                break;
            case '4':
                $query->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 year')));
                break;
            default:
                // 如果没有匹配的筛选条件，不添加任何时间条件
                break;
        }
    }
    /**
     * @return mixed
     * @throws \think\exception\DbException
     * note:显示首页的主要内容
     */
    public function index($keyword)
    {

        // 判断是否为空，为空则返回首页
        if (empty($keyword)) {
            return redirect('/');
        }
        $more = input('get.more'); // 排序
        // 构造时间筛选条件
        // 获取数据
        $list = ResourceModel::with('category')
            ->where('title', 'like', '%' . $keyword . '%')
            ->where(function ($query) use ($more) {
                $this->buildTimeCondition($query, $more);
            })
            ->order('created_at', 'DESC')
            ->paginate(15, false, ['query' => ['keyword' => $keyword]]);
        $count = $list->total();

        // 渲染分页组
        $page = $list->render();
        // 模板渲染 resources数据和分页组
        View::assign('keyword', $keyword);
        View::assign('count', $count);
        View::assign('list', $list);
        View::assign('page', $page);
        View::assign('selected', $more);
        // 返回视图
        return View::fetch('list',[
            'site_title' => getConfig('site_title'),
            'site_keywords' => getConfig('site_keywords'),
            'site_description' => getConfig('site_description'),
            'site_logo_url' => getConfig('site_logo_url'),
            'footer_copyright' => getConfig('footer_copyright'),
        ]);
    }



    /**
     * 显示资源信息，相当于查看更多
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     */
    public function resources($id)
    {
        // 读取当前资源的详细信息
        $resource = ResourceModel::with('category')->where('id', $id)->find();

        if (!$resource) {
            // 如果没有找到该资源，可以返回一个错误页面或其他处理
            return $this->error('资源不存在', '/');
        }

        // 随机获取两个字符作为关键词，读取相关资源
        // 获取标题的长度
        $title_length = mb_strlen($resource['title'], 'UTF-8');

        // 如果标题长度小于2，直接使用整个标题作为关键词
        if ($title_length <= 2) {
            $keyword = $resource['title'];
        } else {
            // 生成一个随机的起始位置，确保不会超出范围
            $start_position = rand(0, $title_length - 2);

            // 随机截取两个字符作为关键词
            $keyword = mb_substr($resource['title'], $start_position, 2, 'UTF-8');
        }
        $relatedResources = Db::name('resources')
            ->field('id, title, url, code, created_at')
            ->where('title', 'like', '%' . $keyword . '%') // 搜索标题包含关键词的资源
            ->where('id', '<>', $id) // 排除当前资源
            ->limit(10) // 限制返回的相关资源数量
            ->select();

        // 将数据分配给模板
        View::assign('data', $resource);
        View::assign('relatedResources', $relatedResources);

        // 返回模板
        return View::fetch('resource');
    }

    /**
     * 暂时这么写，通过分类显示博客
     * @param $class
     * @return mixed
     */
    public function showByClass($class)
    {

        $list = Db::name('resources')->field('id,title,code,time,class')->where('class', $class)
            ->order('time DESC')->paginate(5);
        // 获取分页显示

        $page = $list->render();
        //渲染分页按钮

        //渲染最近文章
        $latter = Db::name('resources')->field('title,id')
            ->order('time DESC')->limit(5)->select();


        View::assign('latter', $latter);
        View::assign('page', $page);
        View::assign('list', $list);


        //返回视图
        return View::fetch('showresourcesbyclass');
    }
    // 更新或插入搜索历史记录
    

}
