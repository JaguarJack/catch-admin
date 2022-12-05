<?php

namespace Modules\Develop\Http\Controllers;

use Catch\Base\CatchController;
use Catch\Support\Module\ModuleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
}
