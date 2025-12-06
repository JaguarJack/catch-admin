<?php

namespace Modules\Common\Repository\Options;

use Catch\CatchAdmin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class Models implements OptionInterface
{
    /**
     * @throws \ReflectionException
     */
    public function get(): array|Collection
    {
        return admin_cache('module_models', 300, function () {
            $models = [];
            $modules = File::directories(base_path('modules'));
            $modelTables = [];
            foreach ($modules as $modulePath) {
                $moduleName = pathinfo($modulePath)['basename'];

                try {
                    $moduleInstaller = CatchAdmin::getModuleInstaller($moduleName);
                } catch (\Throwable $e) {
                    continue;
                }

                $info = $moduleInstaller->getInfo();
                $models[$moduleName] = [
                    'title' => $info['title'],
                ];
                $moduleModels = [];
                $modelFiles = File::glob(CatchAdmin::getModuleModelPath($info['name']).'*.php');
                $modelNamespace = CatchAdmin::getModuleModelNamespace($info['name']);
                foreach ($modelFiles as $modelFile) {
                    $modelClass = $modelNamespace.pathinfo($modelFile, PATHINFO_FILENAME);
                    $class = new \ReflectionClass($modelClass);
                    if ($class->isTrait()) {
                        continue;
                    }

                    $model = new $modelClass;

                    if ($model instanceof Model) {
                        $table = $model->getTable();
                        $modelTables[] = $table;
                        $moduleModels[] = [
                            'model' => $modelClass,
                            'table' => $table,
                            'fields' => $model->getFillable(),
                        ];
                    }
                }

                $models[$moduleName]['models'] = $moduleModels;
            }


            $tableNames = array_column(get_all_tables(), 'name');

            $models['other'] = [
                'title' => '其他',
                'models' => [],
            ];
            foreach ($tableNames as $table) {
                if (! in_array($table, $modelTables)) {
                    $models['other']['models'][] = [
                        'model' => $table,
                        'table' => $table,
                        'fields' => Schema::getColumnListing($table),
                    ];
                }
            }

            return array_values($models);
        });
    }
}
