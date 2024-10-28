<?php
// app/common/model/Resource.php
namespace app\admin\model;

use think\Model;

class Resource extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'resources';

    // // 定义时间戳字段名
    // protected $createTime = 'created_at';
    // protected $updateTime = 'updated_at';

    // // 自动写入时间戳
    // protected $autoWriteTimestamp = true;

    // // 数据验证规则
    // protected $rule = [
    //     'title'  => 'require|max:255',
    //     'content' => 'max:255',
    //     'class'  => 'require|number',
    //     'url'    => 'require|max:255',
    //     'code'   => 'max:10',
    // ];

    // // 错误消息
    // protected $message = [
    //     'title.require' => '资源标题不能为空',
    //     'title.max'     => '资源标题不能超过255个字符',
    //     'content.max'   => '资源简介不能超过255个字符',
    //     'class.require' => '请选择网盘分类',
    //     'class.number'  => '网盘分类必须是数字',
    //     'url.require'   => '网盘链接不能为空',
    //     'url.max'       => '网盘链接不能超过255个字符',
    //     'code.max'      => '提取码不能超过10个字符',
    // ];
    public function category()
    {
        return $this->belongsTo('Category', 'category_id', 'id');
    }
}