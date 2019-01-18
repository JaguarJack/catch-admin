<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18
 * Time: 10:36
 */
namespace app\admin\controller;

use think\Db;

class Database extends Base
{
    /**
     * 数据字典列表
     *
     * @time at 2019年01月18日
     * @return mixed
     */
    public function index()
    {
        $this->tables = Db::query('SHOW TABLE STATUS');

        return $this->fetch();
    }

    /**
     * 优化表
     *
     * @time at 2019年01月18日
     * @return void
     */
    public function optimize()
    {
        $table = $this->request->post('table');

        if (!$table) {
            $this->error('参数错误, 未指定表');
        }

        Db::query(sprintf('optimize table %s', $table)) ? $this->success('优化成功') : $this->error('优化失败');

    }

    /**
     *
     *
     * @time at 2019年01月18日
     * @return void
     */
    public function view()
    {
        $table = $this->request->param('table');

        if (!$table) {
            $this->error('参数错误', '未指定表');
        }

        $this->table = Db::query('show full columns from ' . $table);

        return $this->fetch();
    }
}