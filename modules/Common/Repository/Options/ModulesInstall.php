<?php

namespace Modules\Common\Repository\Options;

use Catch\CatchAdmin;
use Catch\Support\Module\ModuleRepository;

class ModulesInstall implements OptionInterface
{
    public function get(): array
    {
        $modules = [];

        $enabledModuleNames = app(ModuleRepository::class)->getEnabled()->pluck('name')->merge(
            config('catch.module.default', [])
        );

        foreach (CatchAdmin::getModulesPath() as $module) {
            try {
                $installer = CatchAdmin::getModuleInstaller(basename($module));

                $info = $installer->getInfo();

                if (! $enabledModuleNames->contains($info['name'])) {
                    $modules[] = [
                        'label' => $info['title'],
                        'value' => $info['name'],
                    ];
                }
            } catch (\Exception $e) {

            }
        }

        return $modules;
    }
}
