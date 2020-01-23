<?php
/**
 * @filename  MigrateRollbackCommand.php
 * @createdAt 2020/1/20
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */

namespace catcher\command;

use catcher\CatchAdmin;
use Phinx\Db\Adapter\AdapterFactory;
use Phinx\Migration\MigrationInterface;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\facade\Console;
use think\migration\command\migrate\Rollback;
use think\migration\command\migrate\Run;

class MigrateRollbackCommand extends Rollback
{
  protected $module;

  protected function configure()
  {
    $this->setName('catch-migrate:rollback')
      ->setDescription('Rollback the last or to a specific migration')
      ->addArgument('module', Argument::REQUIRED, 'migrate the module database')
      ->addOption('--target', '-t', InputOption::VALUE_REQUIRED, 'The version number to rollback to')
      ->addOption('--date', '-d', InputOption::VALUE_REQUIRED, 'The date to rollback to')
      ->addOption('--force', '-f', InputOption::VALUE_NONE, 'Force rollback to ignore breakpoints')
      ->setHelp(<<<EOT
The <info>catch-migrate:rollback</info> command reverts the last migration, or optionally up to a specific version

<info>php think catch-migrate:rollback</info>
<info>php think catch-migrate:rollback module -t 20111018185412</info>
<info>php think catch-migrate:rollback module -d 20111018</info>
<info>php think catch-migrate:rollback -v</info>

EOT
      );
  }

  /**
   * Rollback the migration.
   *
   * @param Input $input
   * @param Output $output
   * @return void
   */
  protected function execute(Input $input, Output $output)
  {
    $this->module = $input->getArgument('module');
    $version = $input->getOption('target');
    $date = $input->getOption('date');
    $force = !!$input->getOption('force');

    // rollback the specified environment
    $start = microtime(true);
    if (null !== $date) {
      $this->rollbackToDateTime(new \DateTime($date), $force);
    } else {
      if (!$version) {
        $migrations = glob(CatchAdmin::moduleMigrationsDirectory($this->module) . '*.php');
        foreach ($migrations as $migration) {
          $version = explode('_', basename($migration))[0];
          $this->rollback($version, $force);
        }
      } else {
        $this->rollback($version, $force);
      }
    }
    $end = microtime(true);
    $this->migrations = null;
    $output->writeln('');
    $output->writeln('<comment>All Done. Took ' . sprintf('%.4fs', $end - $start) . '</comment>');
  }

  /**
   * 获取 migration path
   *
   * @time 2019年12月03日
   * @return string
   */
  protected function getPath(): string
  {
    return CatchAdmin::moduleMigrationsDirectory($this->module);
  }

  /**
   *
   * @time 2020年01月21日
   * @param null $version
   * @param bool $force
   * @return void
   */
  protected function rollback($version = null, $force = false)
  {
      $migrations = $this->getMigrations();
      $versionLog = $this->getVersionLog();
      $versions = array_keys($versionLog);

      if ($version) {
          $this->executeMigration($migrations[$version], MigrationInterface::DOWN);
      } else {
          foreach ($migrations as $key => $migration) {
              if (in_array($key, $versions)) {
                  $this->executeMigration($migration, MigrationInterface::DOWN);
              }
          }
      }
  }
}
