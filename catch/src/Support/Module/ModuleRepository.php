<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------


declare(strict_types=1);

namespace Catch\Support\Module;

use Catch\Contracts\ModuleRepositoryInterface;
use Catch\Events\Module\Created;
use Catch\Events\Module\Creating;
use Catch\Events\Module\Deleted;
use Catch\Events\Module\Updated;
use Catch\Events\Module\Updating;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

/**
 * FileDriver
 */
class ModuleRepository
{
    protected ModuleRepositoryInterface $moduleRepository;

    /**
     * construct
     */
    public function __construct(ModuleRepositoryInterface $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * all
     *
     * @param array $search
     * @return Collection
     */
    public function all(array $search): Collection
    {
        return $this->moduleRepository->all($search);
    }

    /**
     * create module json
     *
     * @param array $module
     * @return bool
     */
    public function create(array $module): bool
    {
        $module['name'] = lcfirst($module['path']);

        Event::dispatch(new Creating($module));

        $this->moduleRepository->create($module);

        Event::dispatch(new Created($module));

        return true;
    }

    /**
     * module info
     *
     * @param string $name
     * @return Collection
     * @throws Exception
     */
    public function show(string $name): Collection
    {
        try {
            return $this->moduleRepository->show($name);
        } catch (Exception $e) {
            throw new $e();
        }
    }

    /**
     * update module json
     *
     * @param string $name
     * @param array $module
     * @return bool
     */
    public function update(string $name, array $module): bool
    {
        $module['name'] = lcfirst($module['path']);

        Event::dispatch(new Updating($name, $module));

        $this->moduleRepository->update($name, $module);

        Event::dispatch(new Updated($name, $module));

        return true;
    }

    /**
     * delete module json
     *
     * @param string $name
     * @return bool
     * @throws Exception
     */
    public function delete(string $name): bool
    {
        $module = $this->show($name);

        $this->moduleRepository->delete($name);

        Event::dispatch(new Deleted($module));

        return true;
    }

    /**
     * disable or enable
     *
     * @param string $name
     * @return bool|int
     */
    public function disOrEnable(string $name): bool|int
    {
        return $this->moduleRepository->disOrEnable($name);
    }

    /**
     * get enabled
     *
     * @return Collection
     */
    public function getEnabled(): Collection
    {
        // TODO: Implement getEnabled() method.
        return $this->moduleRepository->getEnabled();
    }

    /**
     * enabled
     *
     * @param string $moduleName
     * @return bool
     */
    public function enabled(string $moduleName): bool
    {
        return $this->moduleRepository->enabled($moduleName);
    }
}
