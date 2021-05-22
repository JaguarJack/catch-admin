<?php
// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\cms\tables\forms;
use catchAdmin\cms\support\Table;
use catchAdmin\cms\support\TableColumn;
use catcher\library\form\Form;
use think\facade\Db;
use think\helper\Str;

class Model extends Form
{
    protected $table = 'cms_articles';

    /**
     * create form
     *
     * @time 2021年03月02日
     * @return array
     */
    public function fields(): array
    {
        // TODO: Implement create() method.
        return [
            self::input('name', '模型名称')->required()->maxlength(100),
            self::input('alias', '模型别名')->required()->appendValidates([
                self::validateAlphaDash()
            ]),
            self::select('table_name', '模型关联表')
                ->options($this->getCMSTables())
                ->style(['width' => '100%'])
                ->required(),

            self::textarea('description', '模型描述')->maxlength(255),
        ];
    }


    /**
     * 获取 Cms 表
     *
     * @time 2021年04月30日
     * @return mixed
     */
    protected function getCMSTables()
    {
        $options = self::options();

        foreach (Db::getTables() as $table) {
            if (Str::contains($table, 'cms') && !Str::contains($table, 'relate')) {
                $options->add($table, $table);
            }
        }

        return $options->render();
    }
}