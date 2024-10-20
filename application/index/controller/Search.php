<?php

namespace app\index\controller;

use app\common\controller\BaseController;

class Search extends BaseController
{



    public function _empty()
    {
        return $this->redirect('/');

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
            return $this->redirect('/');
        }



        $more = input('get.more'); // 排序
        if ($more == 1) {
            //倒序
            $sort = 1;
        } else {
            //正序
            $sort = 0;
        }

        // 搜索关键字
        $list = db('resources')->field('id,title,time,size,class')
            ->where('title', 'like', '%' . $keyword . '%')
            ->order('time DESC')->paginate(15, false, ['query' => ['sort' => $sort]]);

        // 获取条数
        $count = $list->total();

        // 渲染分页组
        $page = $list->render();
        // 模板渲染 resources数据和分页组
        $this->assign('keyword', $keyword);
        $this->assign('count', $count);
        $this->assign('list', $list);
        $this->assign('page', $page);

        // 返回视图
        return $this->fetch('list');
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
        $resource = db('resources')->field('id, title, content, url, size, code, time, class')->where('id', $id)->find();

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
        $relatedResources = db('resources')
            ->field('id, title, url, code, time')
            ->where('title', 'like', '%' . $keyword . '%') // 搜索标题包含关键词的资源
            ->where('id', '<>', $id) // 排除当前资源
            ->limit(10) // 限制返回的相关资源数量
            ->select();

        // 将数据分配给模板
        $this->assign('data', $resource);
        $this->assign('relatedResources', $relatedResources);

        // 返回模板
        return $this->fetch('resource');
    }

    /**
     * 暂时这么写，通过分类显示博客
     * @param $class
     * @return mixed
     */
    public function showByClass($class)
    {

        $list = db('resources')->field('id,title,code,time,class')->where('class', $class)
            ->order('time DESC')->paginate(5);
        // 获取分页显示

        $page = $list->render();
        //渲染分页按钮

        //渲染最近文章
        $latter = db('resources')->field('title,id')
            ->order('time DESC')->limit(5)->select();


        $this->assign('latter', $latter);
        $this->assign('page', $page);
        $this->assign('list', $list);


        //返回视图
        return $this->fetch('showresourcesbyclass');
    }
    // 更新或插入搜索历史记录
    // 举报资源
    public function report()
    {
        $id = input('post.id');
        $reason = input('post.reason');
        $details = input('post.details');
        $contact = input('post.contact');

        // 数据验证，确保输入不为空
        if (empty($id) || empty($reason)) {
            return json(['code' => 0, 'msg' => '请填写完整的举报信息']);
        }

        // 检查是否已经举报过该资源（可选）
        $existingReport = db('report')->where('resource_id', $id)->whereTime('time', '-1 hour')->find();
        if ($existingReport) {
            return json(['code' => 0, 'msg' => '您已经举报过该资源']);
        }

        // 准备要插入的数据
        $data = [
            'resource_id' => $id,
            'reason' => $reason, // 举报原因
            'details' => $details, // 举报详细信息
            'contact' => $contact, // 联系方式
        ];

        // 插入举报数据
        $result = db('report')->insert($data);

        // 返回结果
        if ($result) {
            return json(['code' => 1, 'msg' => '举报成功']);
        } else {
            return json(['code' => 0, 'msg' => '举报失败']);
        }
    }
    //检测资源是否有效
    //传入url
    //返回json
    public function checkUrl()
    {
        $url = input('get.url');
        // 发送请求到目标URL
        $url = 'https://api.skyour.cn/api.php?url='.$url;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
        if ($data) {
            return json(['code' => $data['code'],'msg' => $data['msg']]);
        }else{
            return json(['code' => 0,'msg' => '链接无效']);
        }


    }

}
