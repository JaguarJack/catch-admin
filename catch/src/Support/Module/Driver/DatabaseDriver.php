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

namespace Catch\Support\Module\Driver;

use Catch\CatchAdmin;
use Catch\Contracts\ModuleRepositoryInterface;
use Catch\Enums\Status;
use Catch\Exceptions\FailedException;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * DatabaseDriver
 */
class DatabaseDriver implements ModuleRepositoryInterface
{
    protected Model $model;

    public function __construct()
    {
        $this->model = $this->createModuleModel();
    }

    /**
     * all
     *
     * @param array $search
     * @return Collection
     */
    public function all(array $search): Collection
    {
        return $this->model::query()
                    ->when($search['name'] ?? false, function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search['name'].'%');
                    })->get();
    }

    /**
     * create module json
     *
     * @param array $module
     * @return bool|int
     */
    public function create(array $module): bool|int
    {
        $this->hasSameModule($module);

        return $this->model->save([
            'name' => $module['name'],
            'path' => $module['path'],
            'description' => $module['desc'],
            'keywords' => $module['keywords'],
            'service' => sprintf('\\%s%s', CatchAdmin::getModuleNamespace($module['name']), ucfirst($module['name']).'ServiceProvider'),
        ]);
    }

    /**
     * module info
     *
     * @param string $name
     * @return Collection
     */
    public function show(string $name): Collection
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * update module json
     *
     * @param string $name
     * @param array $module
     * @return bool|int
     */
    public function update(string $name, array $module): bool|int
    {
        return $this->model->where('name', $name)

            ->update([
                'name' => $module['name'],
                'alias' => $module['alias'],
                'description' => $module['desc'],
                'keywords' => $module['keywords'],
            ]);
    }

    /**
     * delete module json
     *
     * @param string $name
     * @return bool|int
     */
    public function delete(string $name): bool|int
    {
        return $this->model->where('name', $name)->delete();
    }

    /**
     * disable or enable
     *
     * @param $name
     * @return bool|int
     */
    public function disOrEnable($name): bool|int
    {
        $module = $this->show($name);

        $module->status = (int) $module->status;

        return $module->save();
    }

    /**
     * get enabled
     *
     * @return Collection
     */
    public function getEnabled(): Collection
    {
        // TODO: Implement getEnabled() method.
        return $this->model->where('status', Status::Enable->value())->get();
    }

    /**
     *
     * @param array $module
     * @return void
     */
    protected function hasSameModule(array $module): void
    {
        if ($this->model->where('name', $module['name'])->first()) {
            throw new FailedException(sprintf('Module [%s] has been created', $module['name']));
        }

        if ($this->model->where('alias', $module['alias'])->first()) {
            throw new FailedException(sprintf('Module Alias [%s] has been exised', $module['alias']));
        }
    }

    /**
     * create model
     * @return Model
     */
    protected function createModuleModel(): Model
    {
        return new class () extends Model {
            protected $table;

            public function __construct(array $attributes = [])
            {
                parent::__construct($attributes);

                $this->table = Container::getInstance()->make('config')->get('catch.module.driver.table_name');
            }
        };
    }
}
