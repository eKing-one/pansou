<?php
// app/common/model/User.php
namespace app\common\model;

use think\Model;

class User extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'users';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 数据验证规则
    protected $rule = [
        'name'  => 'require|max:255',
        'email' => 'require|email|max:255',
        'password' =>'require|max:255',

    ];

    // 错误消息
    protected $message = [
        'name.require' => '用户名不能为空',
        'name.max'     => '用户名不能超过255个字符',
        'email.require' => '邮箱不能为空',
        'email.email'  => '邮箱格式不正确',
        'email.max'    => '邮箱不能超过255个字符',
        'password.require' => '密码不能为空',
        'password.max'     => '密码不能超过255个字符',

    ];
    
    
}