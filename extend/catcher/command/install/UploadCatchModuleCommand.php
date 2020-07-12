<?php
/**
 * @filename UploadCatchModuleCommand.php
 * @date     2020/7/11
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catcher\command\install;

use catcher\CatchAdmin;
use catcher\exceptions\FailedException;
use catcher\facade\Http;
use catcher\library\Compress;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Console;

class UploadCatchModuleCommand extends Command
{
    protected $module;

    protected $moduleZipPath;

    /**
     * @var Compress
     */
    protected $compress;

    protected function configure()
    {
        $this->setName('upload:module')
            ->addArgument('module', Argument::REQUIRED, 'module name')
            ->setDescription('install catch module');
    }

    protected function execute(Input $input, Output $output)
    {
        // 打包项目
        // 认证用户
        // 上传
    }

    protected function uploadAddress()
    {

    }

    protected function upload()
    {

    }

    protected function compressModule()
    {

    }
}