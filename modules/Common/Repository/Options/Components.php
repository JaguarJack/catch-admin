<?php

namespace Modules\Common\Repository\Options;

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
            $components = File::glob(CatchAdmin::getModuleViewsPath($module).'*'.DIRECTORY_SEPARATOR.'*.vue');

            foreach ($components as $component) {
                $_component = Str::of($component)
                                ->replace(CatchAdmin::moduleRootPath(), '')
                                ->explode(DIRECTORY_SEPARATOR);
                $_component->shift(2);

                $this->components[] = [
                    'label' => Str::of($_component->implode('/'))->replace('.vue', ''),

                    'value' => Str::of($component)->replace(CatchAdmin::moduleRootPath(), '')->prepend('/')
                ];
            }
        }

        return $this->components;
    }
}
