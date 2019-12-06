<?php
namespace catcher\base;

use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\TransTrait;
use think\model\concern\SoftDelete;

abstract class BaseModel extends \think\Model
{
    use SoftDelete;
    use TransTrait;
    use BaseOptionsTrait;

    protected $createTime = 'create_at';

    protected $updateTime = 'update_at';

    protected $deleteTime = 'delete_at';

}
