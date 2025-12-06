<?php

declare(strict_types=1);

namespace Modules\System\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Modules\Develop\Support\Generate\Create\Enumer;
use Modules\System\Models\Dictionary;
use Modules\System\Http\Requests\DictionaryRequest;
use Catch\Enums\Status;

/**
 * @group 系统管理
 *
 * @subgroup 字典管理
 *
 * @subgroupDescription CatchAdmin 后台系统管理->字典
 */
class DictionaryController extends Controller
{
    public function __construct(
        protected readonly Dictionary $model
    ) {
    }

    /**
     * 字典列表
     *
     * @urlParam name string 字典名称
     * @urlParam status int 状态
     * @urlParam key int 字典键名
     *
     * @responseField code int 状态码
     * @responseField message string 提示信息
     * @responseField limit int 每页数量
     * @responseField page int 当前页
     * @responseField total int 总数
     * @responseField data object[] 数据
     * @responseField data[].id int ID
     * @responseField data[].name string 名称
     * @responseField data[].key int 键
     * @responseField data[].value description 描述
     * @responseField data[].status int 状态
     *
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->model->getList();
    }

    /**
     * 新增字典
     *
     * @bodyParam name string required 名称
     * @bodyParam key string required 键
     * @bodyParam description string 描述
     *
     * @param DictionaryRequest $request
     * @return mixed
     */
    public function store(DictionaryRequest $request)
    {
        return $this->model->storeBy($request->all());
    }

    /**
     * 字典详情
     *
     * @urlParam id int required 字典ID
     *
     * @responseField code int 状态码
     * @responseField message string 提示信息
     * @responseField data object 字典详情
     * @responseField data.id int ID
     * @responseField data.name string 名称
     * @responseField data.key string 键
     * @responseField data.description string 描述
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return $this->model->firstBy($id);
    }

    /**
     * 更新字典
     *
     * @urlParam id int required 字典ID
     *
     * @bodyParam name string required 名称
     * @bodyParam key string required 键
     * @bodyParam description string 描述
     *
     * @param $id
     * @param DictionaryRequest $request
     * @return mixed
     */
    public function update($id, DictionaryRequest $request)
    {
        return $this->model->updateBy($id, $request->all());
    }

    /**
     * 删除字典
     *
     * @urlParam id int required 字典ID
     *
     * @param $id
     * @return false
     */
    public function destroy($id)
    {
        $dictionary = $this->model->find($id);

        if ($this->model->deleteBy($id)) {
            return $dictionary->values()->delete();
        }

        return false;
    }

    /**
     * 启用/禁用 字典
     *
     * @urlParam id int required 字典ID
     *
     * @param $id
     * @return bool
     */
    public function enable($id)
    {
        return $this->model->toggleBy($id);
    }

    /**
     * 字典枚举值
     *
     * @urlParam id int required 字典ID
     *
     * @responseField code int 状态码
     * @responseField message string 提示信息
     * @responseField data boolean 是否成功
     *
     * @return true
     * @throws FileNotFoundException
     */
    public function enums($id)
    {
        $this->model->where('status', Status::Enable)
             ->when($id, fn ($query) => $query->where('id', $id))
             ->with('values')
             ->get()
             ->each(function ($item) {
                 $values = $item->values->toArray();
                 if (count($values)) {
                     $enumer = new Enumer($item->name, $item->description, Str::of($item->key)->studly()->toString(), $item->values->toArray());
                     $enumer->create();
                 }
             });

        return true;
    }
}
