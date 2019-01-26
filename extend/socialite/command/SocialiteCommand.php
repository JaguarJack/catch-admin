<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/29
 * Time: 16:19
 */
namespace thinking\socialite\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class SocialiteCommand extends Command
{
    protected function configure()
    {
        $this->setName('socialite publish')
             ->setDescription('publish socialite config');
    }

    protected function execute(Input $input, Output $output)
    {
        $config = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'socialite.php';

        copy($config, app('config_path'));

        $output->writeln('publish successfully, check it' . PHP_EOL);
    }
}