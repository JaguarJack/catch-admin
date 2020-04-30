<?php
namespace catcher\generate\factory;

use catcher\CatchAdmin;
use catcher\exceptions\FailedException;
use JaguarJack\MigrateGenerator\MigrateGenerator;
use think\facade\Db;
use think\helper\Str;


class Migration extends Factory
{
    public function done($params)
    {
        [$module, $tableName] = $params;

        // TODO: Implement done() method.
        $migrationPath =  CatchAdmin::directory() . $module . DIRECTORY_SEPARATOR.
            'database' . DIRECTORY_SEPARATOR . 'migration' .DIRECTORY_SEPARATOR;

        CatchAdmin::makeDirectory($migrationPath);

        $migrateGenerator = (new MigrateGenerator('thinkphp'));

        $tables = $migrateGenerator->getDatabase()->getAllTables($tableName);

        $version = date('YmdHis');

        $file = $migrationPath . $version . '_'. $tableName . '.php';

        foreach ($tables as $table) {
            if ($table->getName() == $tableName) {
                file_put_contents($file, $migrateGenerator->getMigrationContent($table));
                if (!file_exists($file)) {
                    throw new FailedException('migration generate failed');
                }
                $model = new class extends \think\Model {
                    protected $name = 'migrations';
                };

                $model->insert([
                    'version' => $version,
                    'migration_name' => ucfirst(Str::camel($tableName)),
                    'start_time' => date('Y-m-d H:i:s'),
                    'end_time'  => date('Y-m-d H:i:s')
                ]);
                break;
            }
        }

        return true;
    }
}