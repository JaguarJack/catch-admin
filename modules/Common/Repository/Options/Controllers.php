<?php

namespace Modules\Common\Repository\Options;

use Catch\CatchAdmin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Controllers implements OptionInterface
{
    public function get(): array
    {
        $controllers = [];

        if ($module = request()->get('module')) {
            $controllerFiles = File::glob(CatchAdmin::getModuleControllerPath($module).'*.php');

            foreach ($controllerFiles as $controllerFile) {
                $controllers[] = [
                    'label' => Str::of(File::name($controllerFile))->lcfirst()->remove('Controller'),

                    'value' => Str::of(File::name($controllerFile))->lcfirst()->remove('Controller'),
                ];
            }
        }

        return $controllers;
    }
}
