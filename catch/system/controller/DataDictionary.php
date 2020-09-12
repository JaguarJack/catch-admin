<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchRequest as Request;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use catcher\library\BackUpDatabase;
use think\Collection;
use think\facade\Db;
use think\Paginator;

class DataDictionary extends CatchController
{
    /**
     *
     * @time 2019年12月13日
     * @param Request $request
     * @return \think\response\Json
     */
    public function tables(Request $request): \think\response\Json
    {
        $tables = Db::query('show table status');

        // 重组数据
        foreach ($tables as &$table) {
            $table = array_change_key_case($table);
            $table['index_length'] = $table['index_length'] > 1024 ? intval($table['index_length']/1024) .'MB' : $table['index_length'].'KB';
            $table['data_length'] = $table['data_length'] > 1024 ? intval($table['data_length']/1024) .'MB' : $table['data_length'].'KB';
            $table['create_time'] = date('Y-m-d', strtotime($table['create_time']));
        }

        // 搜素
        $tables = new Collection($tables);
        // 名称搜索
        if ($name = $request->get('tablename', null)) {
            $tables = $tables->where('name', 'like', $name)->values();
        }
        // 引擎搜索
        if ($engine = $request->get('engine', null)) {
            $tables =  $tables->where('engine', $engine)->values();
        }
        $page = $request->get('page', 1) ? : 1;
        $limit = $request->get('limit', 10);

        return CatchResponse::paginate(Paginator::make(array_slice($tables->toArray(), ($page - 1) * $limit, $limit), $limit, $page, $tables->count(), false, []));
    }

  /**
   *
   * @time 2019年12月13日
   * @param $table
   * @return \think\response\Json
   * @throws \Exception
   */
    public function view($table): \think\response\Json
    {
        return CatchResponse::success(array_values(Db::getFields($table)));
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
    public function backup(BackUpDatabase $backUpDatabase): \think\response\Json
    {
        try {
            $backUpDatabase->done(trim(implode(',', \request()->post('data')), ','));
        }catch (\Exception $e) {
            throw new FailedException($e->getMessage());
        }

        return CatchResponse::success([], '备份成功');
    }
}
