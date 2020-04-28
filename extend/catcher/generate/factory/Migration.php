<?php
namespace catcher\generate\factory;

use catcher\CatchAdmin;
use catcher\exceptions\FailedException;
use JaguarJack\MigrateGenerator\MigrateGenerator;


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

        $file = $migrationPath . date('YmdHis') . '_'. $tableName . '.php';

        foreach ($tables as $table) {
            if ($table->getName() == $tableName) {
                file_put_contents($file, $migrateGenerator->getMigrationContent($table));
                if (!file_exists($file)) {
                    throw new FailedException('migration generate failed');
                }
                break;
            }
        }

        return true;
    }
}