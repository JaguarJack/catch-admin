<?php
// +----------------------------------------------------------------------
// | UCToo [ Universal Convergence Technology ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2021 https://www.uctoo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: UCToo <contact@uctoo.com>
// +----------------------------------------------------------------------
namespace catchAdmin\apimanager\repository;

use catchAdmin\permissions\middleware\PermissionsMiddleware;
use catchAdmin\apimanager\model\RouteList;
use catcher\base\CatchRepository;
use catcher\exceptions\FailedException;
use think\facade\Console;
use think\facade\Log;
use think\facade\Db;

class RouteListRepository extends CatchRepository
{
    protected $routeList;

    public function __construct(RouteList $routeList)
    {
        $this->routeList = $routeList;
    }

    protected function model()
    {
        return $this->routeList;
    }

    public function all()
    {
        $routeList = $this->routeList->select();

        return $routeList->toArray();
    }

    /**
     * 同步
     *
     * @time 2020年06月26日
     * @throws \Exception
     * @return bool
     */
    public function sync()
    {
        DB::table('route_list')->delete(true);
        Console::call('route:list', ['-m']);  //没用，也不是从命令生成的route_list文件读的数据，就是想执行一下命令
        $routeList = app()->route->getRuleList();
        $rows      = [];
        foreach ($routeList as $item) {
            $item['route'] = $item['route'] instanceof \Closure ? '<Closure>' : $item['route'];
            $item['option'] = json_encode($item['option']);
            $item['pattern'] = json_encode($item['pattern']);
            $rows[] = $item;
        }
        $res = $this->routeList->saveAll($rows);

        return true;
    }

}