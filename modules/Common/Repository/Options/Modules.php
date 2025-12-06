<?php

namespace Modules\Common\Repository\Options;

use Catch\CatchAdmin;
use Illuminate\Support\Facades\File;

class Modules implements OptionInterface
{
    public function get(): array
    {
        $modules = [];

        foreach (File::directories(CatchAdmin::moduleRootPath()) as $dir) {
            $modules[] = [
                'label' => pathinfo($dir, PATHINFO_BASENAME) . ' 模块',

                'value' => pathinfo($dir, PATHINFO_BASENAME),
            ];
        }

        return $modules;
    }
}
