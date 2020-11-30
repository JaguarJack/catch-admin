<?php

namespace catchAdmin\trade\model;

use catcher\base\CatchModel as Model;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\ScopeTrait;
// 数据库字段映射
class TradeWithdrawal extends Model
{
    use BaseOptionsTrait, ScopeTrait;
    // 表名
    public $name = 'trade_withdrawal';
    public $field = array(
        'id',
        // 操作者ID
        'operate_id',
        // 会员昵称
        'nick_name',
        // 收款账号
        'collect_account',
        // 账号名称
        'account_name',
        // 账号类型 1支付宝 2微信 3银行卡 4其他
        'account_type',
        // 提现金额
        'money',
        // 真实姓名
        'real_name',
        // 提现人手机号
        'member_mobile',
        // 备注
        'remark',
        // 订单号
        'order_id',
        // 处理状态
        'status',
        // 会员ID
        'member_id',
        // 创建人ID
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );
}
