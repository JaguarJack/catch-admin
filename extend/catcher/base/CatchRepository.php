<?php
declare(strict_types=1);

/**
 * @filename  CatchRepository.php
 * @createdAt 2020/6/21
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catcher\base;


/**
 * @method   getList(array $data = [])
 * @method   storeBy(array $data)
 * @method   updateBy(int $id, array $data)
 * @method   findBy(int $id, array $column = ['*'])
 * @method   deleteBy(int $id)
 * @method   disOrEnable(int $id)
 * @method   startTrans()
 * @method   rollback()
 * @method   commit()
 * @method   transaction(\Closure $callback)
 * @method   raw($sql)
 */
abstract class CatchRepository
{
    /**
     * 模型映射方法
     *
     * @time 2019年05月26日
     * @email wuyanwen@baijiayun.com
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (method_exists($this, 'model')) {
            return call_user_func_array([$this->model(), $name], $arguments);//$this->model()->$name(...$arguments);
        }

        throw new \Exception(sprintf('Method %s Not Found~', $name));
    }
}