<?php

namespace Modules\Options\Repository;

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
                    'label' => Str::of(File::name($controllerFile))->remove('Controller'),

                    'value' => Str::of(File::name($controllerFile))->remove('Controller'),
                ];
            }
        }

        return $controllers;
    }
}
