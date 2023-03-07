<?php

namespace Modules\Develop\Http\Controllers;

use Catch\Base\CatchController;
use Catch\CatchAdmin;
use Catch\Contracts\ModuleRepositoryInterface;
use Catch\Exceptions\FailedException;
use Catch\Support\Module\ModuleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\Develop\Support\Generate\Module;
use Modules\Develop\Support\ModuleInstall;

class ModuleController extends CatchController
{
    protected ModuleRepository $repository;

    /**
     * @param ModuleRepository $repository
     */
    public function __construct(ModuleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * index
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        return $this->repository->all($request->all());
    }

    /**
     * store
     *
     * @param Request $request
     * @return bool|int
     */
    public function store(Request $request): bool|int
    {
        return $this->repository->create($request->all());
    }

    /**
     * show
     *
     * @param string $name
     * @return Collection
     * @throws \Exception
     */
    public function show(mixed $name): Collection
    {
        return $this->repository->show($name);
    }

    /**
     * update
     *
     * @param $name
     * @param Request $request
     * @return bool|int
     */
    public function update($name, Request $request): bool|int
    {
        return $this->repository->update($name, $request->all());
    }


    /**
     * update
     *
     * @param $name
     * @return bool|int
     */
    public function enable($name): bool|int
    {
        return $this->repository->disOrEnable($name);
    }

    /**
     * destroy
     *
     * @param $name
     * @return bool|int
     * @throws \Exception
     */
    public function destroy($name): bool|int
    {
        return $this->repository->delete($name);
    }

    /**
     * install
     *
     * @param Request $request
     * @param ModuleRepositoryInterface $moduleRepository
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
     * upload
     *
     * @param Request $request
     * @return string
     */
    public function upload(Request $request)
    {   $file = $request->file('file');

        Storage::build([
            'driver' => 'local',
            'root' => storage_path('app')
        ])->put($file->getClientOriginalName(), $file->getContent());

        return storage_path('app') . DIRECTORY_SEPARATOR . $file->getClientOriginalName();
    }
}
