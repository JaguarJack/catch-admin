<?php
namespace catcher\generate\template;

trait Content
{
    public function header()
    {
        $year = date('Y', time());

        return <<<TMP
<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~{$year} http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------


TMP;
    }

    /**
     * set namespace
     *
     * @time 2020年04月27日
     * @param $namespace
     * @return string
     */
    public function nameSpace($namespace)
    {
        return <<<TMP
namespace {$namespace};


TMP;
    }

}
