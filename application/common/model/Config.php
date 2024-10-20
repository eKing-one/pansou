<?php
namespace app\common\model;

use think\Model;

class Config extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'config';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    public static function updateOrCreate($where, $data)
    {
        $config = self::where($where)->find();
        if ($config) {
            return $config->save($data);
        } else {
            return self::create($data);
        }
    }
}