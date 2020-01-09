<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchRequest as Request;
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
     * @param Request $request
     * @return \think\response\Json
     */
    public function tables(Request $request): \think\response\Json
    {
        $tables = Db::query('show table status');

        $tablename = $request->get('tablename');
        $engine = $request->get('engine');

        $searchTables = [];
        $searchMode = false;
        if ($tablename || $engine) {
          $searchMode = true;
        }

        foreach ($tables as $key => &$table) {
          $table = array_change_key_case($table);
          $table['index_length'] = $table['index_length'] > 1024 ? intval($table['index_length']/1024) .'MB' : $table['index_length'].'KB';
          $table['data_length'] = $table['data_length'] > 1024 ? intval($table['data_length']/1024) .'MB' : $table['data_length'].'KB';
          $table['create_time'] = date('Y-m-d', strtotime($table['create_time']));
          // 搜索
          if ($tablename && !$engine && stripos($table['name'], $tablename) !== false) {
              $searchTables[] = $table;
          }
          // 搜索
          if (!$tablename && $engine && stripos($table['engine'], $engine) !== false) {
              $searchTables[] = $table;
          }

          if ($tablename && $engine && stripos($table['engine'], $engine) !== false && stripos($table['name'], $tablename) !== false) {
            $searchTables[] = $table;
          }
        }


        return CatchResponse::paginate(Paginator::make(!$searchMode ? $tables : $searchTables, $request->get('limit') ?? 10, $request->get('page') ?? 1, $searchMode ? count($searchTables)  : count($tables), false, []));
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
        $fields = Db::query('show full columns from ' . $table);

        array_walk($fields, function (&$item){
            $item = array_change_key_case($item);
        });

        return CatchResponse::success($fields);
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
