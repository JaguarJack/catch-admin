<?php

declare(strict_types=1);

namespace Modules\System\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Modules\System\Http\Requests\DictionaryValueRequest;
use Modules\System\Models\DictionaryValues;

/**
 * @group 系统管理
 *
 * @subgroup 字典值管理
 *
 * @subgroupDescription CatchAdmin 后台系统管理->字典值
 */
class DictionaryValuesController extends Controller
{
    public function __construct(
        protected readonly DictionaryValues $model
    ) {
    }

    /**
     * 字典值管理
     *
     * @urlParam id int required 字典ID
     *
     * @responseField code int 状态码
     * @responseField message string 提示信息
     * @responseField limit int 每页数量
     * @responseField page int 当前页
     * @responseField total int 总数
     * @responseField data object[] 数据
     * @responseField data[].id int ID
     * @responseField data[].label string 名称
     * @responseField data[].key string 键
     * @responseField data[].value description 描述
     * @responseField data[].status int 状态
     * @responseField data[].created_at string 创建时间
     *
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->model->getList();
    }

    /**
     * 新增字典值
     *
     * @bodyParam label string required 字典值名
     * @bodyParam key string required 字典键名
     * @bodyParam value int required 字典键值
     * @bodyParam description string 描述
     * @bodyParam sort int 排序
     * @bodyParam status int 状态
     *
     * @param DictionaryValueRequest $request
     * @return mixed
     */
    public function store(DictionaryValueRequest $request)
    {
        return $this->model->storeBy($request->all());
    }

    /**
     * 字典详情
     *
     * @urlParam id int required 字典值ID
     *
     * @responseField code int 状态码
     * @responseField message string 提示信息
     * @responseField data object 字典详情
     * @responseField data.id int ID
     * @responseField data.label string 名称
     * @responseField data.key string 键
     * @responseField data.value string 字典键值
     * @responseField data.description string 描述
     * @responseField data.status int 状态
     * @responseField data.sort int 排序
     * @responseField data.created_at string 创建时间
     * @responseField data.updated_at string 更新时间
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return $this->model->firstBy($id);
    }

    /**
     * 更新字典值
     *
     * @urlParam id int required 字典值ID
     *
     * @bodyParam label string required 字典值名
     * @bodyParam key string required 字典键名
     * @bodyParam value int required 字典键值
     * @bodyParam description string 描述
     * @bodyParam sort int 排序
     * @bodyParam status int 状态
     *
     * @param $id
     * @param DictionaryValueRequest $request
     * @return mixed
     */
    public function update($id, DictionaryValueRequest $request)
    {
        return $this->model->updateBy($id, $request->all());
    }

    /**
     * 删除字典值
     *
     * @urlParam id int required 字典值ID
     *
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        return $this->model->deleteBy($id);
    }

    /**
     * 禁用/启用 字典值
     *
     * @urlParam id int required 字典值ID
     *
     * @param $id
     * @return bool
     */
    public function enable($id)
    {
        return $this->model->toggleBy($id);
    }
}
