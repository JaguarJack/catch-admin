<?php
/**
 * @filename  MigrateCreateCommand.php
 * @createdAt 2020/1/21
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catcher\command;

use catcher\CatchAdmin;
use Phinx\Util\Util;
use think\console\Input;
use think\console\input\Argument as InputArgument;
use think\console\Output;
use think\exception\InvalidArgumentException;
use think\migration\command\migrate\Create;

class MigrateCreateCommand extends Create
{
  /*
   *
  * {@inheritdoc}
  */
  protected function configure()
  {
    $this->setName('catch-migrate:create')
      ->setDescription('Create a new migration')
      ->addArgument('module', InputArgument::REQUIRED, 'the module where you create')
      ->addArgument('name', InputArgument::REQUIRED, 'What is the name of the migration?')
      ->setHelp(sprintf('%sCreates a new database migration%s', PHP_EOL, PHP_EOL));
  }

  /**
   * Create the new migration.
   *
   * @param Input  $input
   * @param Output $output
   * @return void
   * @throws InvalidArgumentException
   * @throws RuntimeException
   */
  protected function execute(Input $input, Output $output)
  {
    $module = $input->getArgument('module');

    $className = $input->getArgument('name');

    $path = $this->create($module, $className);

    $output->writeln('<info>created</info> .' . str_replace(getcwd(), '', realpath($path)));
  }

  /**
   *
   * @time 2020年01月21日
   * @param $module
   * @param $className
   * @return string
   */
  protected function create($module, $className): string
  {
      $path = CatchAdmin::makeDirectory(CatchAdmin::moduleMigrationsDirectory($module));

      if (!Util::isValidPhinxClassName($className)) {
        throw new InvalidArgumentException(sprintf('The migration class name "%s" is invalid. Please use CamelCase format.', $className));
      }

      if (!Util::isUniqueMigrationClassName($className, $path)) {
        throw new InvalidArgumentException(sprintf('The migration class name "%s" already exists', $className));
      }

      // Compute the file path
      $fileName = Util::mapClassNameToFileName($className);

      $filePath = $path . DIRECTORY_SEPARATOR . $fileName;

      if (is_file($filePath)) {
          throw new InvalidArgumentException(sprintf('The file "%s" already exists', $filePath));
      }

      // Verify that the template creation class (or the aliased class) exists and that it implements the required interface.
      $aliasedClassName = null;

      // Load the alternative template if it is defined.
      $contents = file_get_contents($this->getTemplate());

      // inject the class names appropriate to this migration
      $contents = strtr($contents, [
        'MigratorClass' => $className,
      ]);

      if (false === file_put_contents($filePath, $contents)) {
        throw new RuntimeException(sprintf('The file "%s" could not be written to', $path));
      }

      return $filePath;
  }


  protected function getTemplate()
  {
      return __DIR__ . '/stubs/migrate.stub';
  }
}
