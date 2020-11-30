<?php
declare(strict_types=1);

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
    /**
     * psr4
     *
     * @time 2020年11月19日
     * @return mixed
     */
    public function psr4Autoload()
    {
        return $this->content()['autoload']['psr-4'];
    }

    /**
     * require
     *
     * @time 2020年11月19日
     * @return mixed
     */
    public function requires()
    {
        return $this->content()['require'];
    }

    /**
     * require dev
     *
     * @time 2020年11月19日
     * @return mixed
     */
    public function requireDev()
    {
        return $this->content()['require-dev'];
    }

    /**
     * composer has package
     *
     * @time 2020年11月19日
     * @param $name
     * @return bool
     */
    public function hasPackage($name)
    {
        $packages = array_merge($this->requires(), $this->requireDev());

        return in_array($name, array_keys($packages));
    }

    /**
     * composer content
     *
     * @time 2020年11月19日
     * @return mixed
     */
    protected function content()
    {
        return \json_decode(FileSystem::sharedGet($this->path()), true);
    }

    /**
     * composer path
     *
     * @time 2020年11月19日
     * @return string
     */
    protected function path()
    {
        return root_path() . 'composer.json';
    }
}
