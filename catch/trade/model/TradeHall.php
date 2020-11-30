<?php

namespace catchAdmin\trade\model;

use catcher\base\CatchModel as Model;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\ScopeTrait;
// 数据库字段映射
class TradeHall extends Model
{
    use BaseOptionsTrait, ScopeTrait;
    // 表名
    public $name = 'task_trade_hall';
    public $field = array(
        'id',
        // 大厅代码|input|input|input|input
        'code',
        // 大厅logo|upload-image|image-uploader|image-uploader|
        'hall_img',
        // 大厅名称|input|input|input|input
        'title',
        // 大厅状态|switch|switch|switch
        'status',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );
}