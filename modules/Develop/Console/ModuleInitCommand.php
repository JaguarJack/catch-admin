<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 ~ now https://catchadmin.vip All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace Modules\Develop\Console;

use Catch\CatchAdmin;
use Modules\Develop\Support\Generate\Module;
use Illuminate\Console\Command;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class ModuleInitCommand extends Command
{
    protected $signature = 'catch:module:init';

    protected $description = '初始化一个模块插件';

    /**
     * @return void
     */
    public function handle(): void
    {
        $title = text('模块名称', '请输入模块中文名称', required: true, validate: 'min:2,max:15');

        $name = text('模块标识', '请输入模块标识, 只能是[a-zA-Z]', required: true, validate: 'min:2|max:15|alpha:ascii');

        $isContinue = true;
        if (CatchAdmin::isModulePathExist($name)) {
           $isContinue =  confirm('模块目录已存在，是否继续，继续创建将会覆盖原文件');
        }

        if (! $isContinue) {
            exit;
        }

        $keywords = text('关键词', '请输入模块关键词');

        $description = text('描述', '请输入模块描述');

        $isRequest = confirm('是否创建表单验证目录');

        $module = new Module($name, true, true, $isRequest, true, $title, $keywords, $description);

        $module->create();

        $this->info("[{$title}] 模块插件创建成功,请查看 modules/" . ucfirst($name) . '目录');
    }
}
