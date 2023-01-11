<?php

namespace Modules\Common\Repository\Options;

use Catch\Support\Module\ModuleRepository;

class Modules implements OptionInterface
{
    public function get(): array
    {
        $modules = [];

        app(ModuleRepository::class)->all([])

            ->each(function ($module) use (&$modules) {
                $modules[] = [
                    'label' => $module['title'],

                    'value' => $module['name']
                ];
            });

        return $modules;
    }
}
