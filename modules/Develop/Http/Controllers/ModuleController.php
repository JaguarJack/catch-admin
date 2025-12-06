<?php

namespace Modules\Develop\Http\Controllers;

use Catch\Base\CatchController;
use Catch\Contracts\ModuleRepositoryInterface;
use Catch\Exceptions\FailedException;
use Catch\Support\Module\ModuleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\Develop\Support\ModuleInstall;
use Modules\Permissions\Models\Permissions;

/**
 * @group 开发模块
 *
 * @subgroup 模块管理
 * @subgroupDescription CatchAdmin 后台模块管理
 */
class ModuleController extends CatchController
{
    protected ModuleRepository $repository;

    public function __construct(ModuleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 模块列表
     *
     * @urlParam title string 模块名称
     *
     * @responseField title string 模块名称
     * @responseField path string 模块目录
     * @responseField keywords string 模块关键字
     * @responseField description string 模块描述
     * @responseField enable boolean 是否启用
     * @responseField version string 版本
     *
     * @param  Request  $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        return $this->repository->all($request->all());
    }

    /**
     * 新增模块
     *
     * @bodyParam name string required 模块名称
     * @bodyParam path string required 模块目录
     * @bodyParam keywords string 模块关键字
     * @bodyParam description string 模块描述
     *
     * @param  Request  $request
     * @return bool|int
     */
    public function store(Request $request): bool|int
    {
        return $this->repository->create($request->all());
    }

    /**
     * 模块查询
     *
     * @urlParam name string required 模块名称
     *
     * @param  string  $name
     *
     * @throws \Exception
     */
    public function show(mixed $name): Collection
    {
        return $this->repository->show($name);
    }

    /**
     * 更新模块
     *
     * @urlParam name string required 模块名称
     *
     * @bodyParam name string required 模块名称
     * @bodyParam path string required 模块目录
     * @bodyParam keywords string 模块关键字
     * @bodyParam description string 模块描述
     *
     */
    public function update($name, Request $request): bool|int
    {
        return $this->repository->update($name, $request->all());
    }

    /**
     * 禁用/启用模块
     *
     * @urlParam name string required 模块名称
     *
     * @param  $name
     * @return bool|int
     */
    public function enable($name): bool|int
    {
        if ($enable = $this->repository->disOrEnable($name)) {
            // 权限模块开启的时候
            if ($this->repository->enabled('permissions')) {
                // 如果是关闭状态，将菜单删除
                if (! $this->repository->enabled($name)) {
                    Permissions::where('module', $name)->delete();
                } else {
                    Permissions::where('module', $name)->restores();
                }
            }
        }

        return $enable;
    }

    /**
     * 删除模块
     *
     * @urlParam name string required 模块名称
     *
     * @param  $name
     * @return bool|int
     */
    public function destroy($name): bool|int
    {
        throw new FailedException('模块禁止删除');
    }

    /**
     * 安装模块
     *
     * @bodyParam type string required 模块类型
     * @bodyParam title string required 模块名称
     *
     * @param  Request  $request
     * @param  ModuleRepositoryInterface  $moduleRepository
     * @return true
     */
    public function install(Request $request, ModuleRepositoryInterface $moduleRepository)
    {
        if ($moduleRepository->all()->pluck('name')->contains($request->get('title'))) {
            throw new FailedException('模块已安装，无法再次安装');
        }

        $moduleInstall = new ModuleInstall($request->get('type'));

        $moduleInstall->install($request->all());

        return true;
    }

    /**
     * 上传模块
     *
     * @bodyParam `file` file required 文件
     *
     * @param  Request  $request
     * @return string
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');

        Storage::build([
            'driver' => 'local',
            'root' => storage_path('app'),
        ])->put($file->getClientOriginalName(), $file->getContent());

        return storage_path('app').DIRECTORY_SEPARATOR.$file->getClientOriginalName();
    }
}
