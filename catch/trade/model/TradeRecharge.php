<?php

namespace catchAdmin\trade\model;

use catcher\base\CatchModel as Model;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\ScopeTrait;
// 数据库字段映射
class TradeRecharge extends Model
{
    use BaseOptionsTrait, ScopeTrait;
    // 表名
    public $name = 'trade_recharge';
    public $field = array(
        'id',
        // 操作者ID
        'operate_id',
        // 收款人
        'enter_nane',
        // 收款账号
        'enter_account',
        // 账号名称
        'account_name',
        // 账号类型 1支付宝 2微信 3银行卡 4其他
        'account_type',
        // 充值金额
        'recharge_money',
        // 充值方式
        'recharge_method',
        // 交易凭证
        'payment_voucher',
        // 充值人姓名
        'recharge_name',
        // 充值人手机号
        'recharge_mobile',
        // 备注
        'remark',
        // 订单号
        'order_id',
        // 订单状态
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
