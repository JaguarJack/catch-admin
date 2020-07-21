<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\library;

use catcher\facade\FileSystem;

class Composer
{
    public function psr4Autoload()
    {
        return $this->composerContent()['autoload']['psr-4'];
    }

    public function requires()
    {
        return $this->composerContent()['require'];
    }

    protected function composerContent()
    {
        return \json_decode(FileSystem::get($this->composerJsonPath()), true);
    }

    protected function composerJsonPath()
    {
        return root_path() . 'composer.json';
    }
}
