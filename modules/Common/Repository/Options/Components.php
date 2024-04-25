<?php

namespace Modules\Common\Repository\Options;

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
            'value' => '/layout/index.vue',
        ],
    ];

    public function get(): array
    {
        try {
            $viewRootPath = config('catch.views_path');

            if ($module = request()->get('module')) {
                if (!File::exists($viewRootPath . $module . DIRECTORY_SEPARATOR)) {
                    return [];
                }

                $components = File::allFiles($viewRootPath . $module . DIRECTORY_SEPARATOR);

                foreach ($components as $component) {
                    // 过滤非 vue 文件
                    if ($component->getExtension() !== 'vue') {
                        continue;
                    }

                    $_component = Str::of($component->getPathname())
                        ->replace($viewRootPath, '')
                        ->explode(DIRECTORY_SEPARATOR);

                    $_component->shift(1);

                    $this->components[] = [
                        'label' => Str::of($_component->implode('/'))->replace('.vue', ''),

                        'value' => Str::of($component)->replace($viewRootPath, '')->prepend('/'),
                    ];
                }
            }

            return $this->components;
        } catch (\Throwable $exception) {
            return [];
        }
    }
}
