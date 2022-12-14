<?php

namespace Modules\Options\Repository;

use Catch\CatchAdmin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Components implements OptionInterface
{
    /**
     * @var array|string[]
     */
    protected array $components = [
        [
            'label' => 'layout',
            'value' => '/admin/layout/index.vue'
        ]
    ];

    public function get(): array
    {
        if ($module = request()->get('module')) {
            $components = File::glob(CatchAdmin::getModuleViewsPath($module).'*/*.vue');

            foreach ($components as $component) {
                $this->components[] = [
                    'label' => Str::of($component)->explode(DIRECTORY_SEPARATOR)->pop(2)->pop(),

                    'value' => Str::of($component)->replace(CatchAdmin::moduleRootPath(), '')->prepend('/')
                ];
            }
        }

        return $this->components;
    }
}
