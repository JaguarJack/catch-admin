<?php

namespace catchAdmin\order\model;

use catcher\base\CatchModel as Model;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\ScopeTrait;
// 数据库字段映射
class TradeOrder extends Model
{
    use BaseOptionsTrait, ScopeTrait;
    // 表名
    public $name = 'task_trade_order';
    public $field = array(
        // ID|text|||input
        'id',
        // 开始时间|date
        'start_time',
        // 结束时间|date
        'end_time',
        // 商品ID|text
        'goods_id',
        // 审核意见|text
        'review_remark',
        // 完成凭证|image
        'fulfil_voucher',
        // 提交备注|text
        'submit_remark',
        // 创建人ID
        'creator_id',
        // 创建时间|text|||date
        'created_at',
        // 最近更新时间|text
        'updated_at',
        // 软删除
        'deleted_at',
    );
}