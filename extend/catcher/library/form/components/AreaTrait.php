<?php
namespace catcher\library\form\components;

use catcher\traits\db\RewriteTrait;
use FormBuilder\Factory\Elm;

trait AreaTrait
{
    public function area($field = 'area', $title = '地区', $props = [])
    {
        if (!count($props)) {
            $props = self::props('name', 'id', [
                'checkStrictly' => true
            ]);
        }

        return self::cascader($field, $title)
            ->options($this->getRegion(3))
            ->props($props)
            ->clearable(true)
            ->filterable(true);
    }

    public function createValidate()
    {
        // TODO: Implement createValidate() method.
        return Elm::validateStr();
    }


    /**
     * 支持四级
     * $level 1,2,3,4
     * @time 2021年04月19日
     * @param int $level
     * @return mixed
     */
    protected function getRegion($level = 1)
    {
        $areaModel = new class extends \think\Model {
            protected $name = 'region';

            use RewriteTrait;
        };

        return $areaModel->where('level', '<=', $level)
                        ->field(['id', 'name', 'parent_id'])
                        ->select()->toTree();
    }
}