<?php

namespace catchAdmin\member\model;

use catcher\base\CatchModel as Model;

// 数据库字段映射
class Level extends Model
{
    // 表名
    public $name = 'member_vip';
    public $field = array(
        'id',
        // 等级代码
        'level_code',
        // 等级名称
        'level_title',
        // 最低条件
        'min_factor',
        // 最高条件
        'max_factor',
        // 一级奖励
        'one_level_reward',
        // 二级奖励
        'tow_level_reward',
        // 三级奖励
        'three_level_reward',
        // 有效期
        'expire_day',
        // 有效推荐
        'valid_invite',
        // 每日抢单数
        'reward_count',
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
