<?php

namespace catchAdmin\member\model;

use catcher\base\CatchModel as Model;

// 数据库字段映射
class Member extends Model
{
    // 表名
    public $name = 'member';
    public $field = array(
        'id',
        // 会员头像
        'avatar',
        // 余额合计
        'balance_total',
        // 冻结余额
        'diff_total',
        // 邀请码
        'invite_code',
        // 最后登录时间
        'last_login_time',
        // 登录密码
        'password',
        // 登录密码安全码
        'password_safety',
        // 手机号
        'mobile',
        // 昵称
        'nickname',
        // 个性签名
        'profile',
        // 会员QQ
        'qq',
        // 注册IP
        'register_ip',
        // 备注
        'remark',
        // uuid
        'uuid',
        // 会员到期时间
        'vip_expired_time',
        // 会员等级id
        'vip_level_id',
        // 是否冻结 0否 1是
        'is_frozen',
        // 支付密码
        'pay_password',
        // 支付密码安全码
        'pay_password_safety',
        // 会员状态
        'status',
        // 是否内部用户0否 1是
        'is_inside',
        // 渠道
        'channel',
        // 真实名字
        'real_name',
        // 身份证号码
        'id_card',
        // 收款资料ID
        'collect_id',
        // 推荐人ID
        'parent_id',
        // 创建人ID
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );

    /**
     * 查询列表
     *
     * @time 2020年04月28日
     * @return mixed
     */
    public function getList()
    {
        return $this->withoutField(['updated_at'], true)
            ->catchSearch()
            ->catchJoin(Level::class, 'id', 'vip_level_id', ['level_title'])
            ->order($this->aliasField('id'), 'desc')
            ->paginate();
    }
}
