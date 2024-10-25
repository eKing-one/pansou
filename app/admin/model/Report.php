<?php
// app/common/model/Report.php
namespace app\admin\model;

use think\Model;

class Report extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'report';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}