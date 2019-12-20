<?php
namespace catchAdmin\system\controller;

use app\Request;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use think\facade\Console;
use think\facade\Db;
use think\Paginator;

class DataDictionary extends CatchController
{
    /**
     *
     * @time 2019年12月13日
     * @throws \Exception
     * @return string
     */
    public function index(): string
    {
        return $this->fetch();
    }

    /**
     *
     * @time 2019年12月13日
     * @param Request $request
     * @return \think\response\Json
     */
    public function tables(Request $request): \think\response\Json
    {
        $tables = Db::query('show table status');

        return CatchResponse::paginate(Paginator::make($tables, $request->get('limit') ?? 10, $request->get('page'), count($tables), false, []));
    }

    /**
     *
     * @time 2019年12月13日
     * @param $table
     * @throws \Exception
     * @return string
     */
    public function view($table): string
    {
        $this->table = Db::query('show full columns from ' . $table);

        return $this->fetch();
    }

    /**
     *
     * @time 2019年12月13日
     * @return \think\response\Json
     */
    public function optimize(): \think\response\Json
    {
        $tables = \request()->post('data');

        foreach ($tables as $table) {
            Db::query(sprintf('optimize table %s', $table));
        }

        return CatchResponse::success([], '优化成功');
    }

    /**
     *
     * @time 2019年12月13日
     * @throws FailedException
     * @return \think\response\Json
     */
    public function backup(): \think\response\Json
    {
        try {
            Console::call('backup:data', [trim(implode(',', \request()->post('data')), ',')]);
        }catch (\Exception $e) {
            throw new FailedException($e->getMessage());
        }

        return CatchResponse::success([], '备份成功');
    }
}
