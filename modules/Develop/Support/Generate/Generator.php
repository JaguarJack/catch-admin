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

namespace Modules\Develop\Support\Generate;

use Catch\Exceptions\FailedException;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Modules\Develop\Support\Generate\Create\Controller;
use Modules\Develop\Support\Generate\Create\FrontForm;
use Modules\Develop\Support\Generate\Create\FrontTable;
use Modules\Develop\Support\Generate\Create\Model;
use Modules\Develop\Support\Generate\Create\Request;
use Modules\Develop\Support\Generate\Create\Route;

/**
 * @class Generator
 */
class Generator
{
    /**
     * @var array{module:string,controller:string,model:string,paginate: bool,schema: string}
     */
    protected array $gen;

    /**
     * @var array{name: string,charset: string, collection: string,
     *      comment:string,created_at: bool, updated_at: bool, deleted_at: bool,
     *      creator_id: bool, updated_at: bool, engine: string}
     */
    protected array $schema;

    /**
     * @var array
     */
    protected array $structures;

    /**
     * @var array
     */
    protected array $files = [];


    /**
     * this model name from controller
     *
     * @var string
     */
    protected string $modelName;


    /**
     * this request name for controller
     *
     * @var ?string
     */
    protected ?string $requestName;

    /**
     * generate
     *
     * @throws Exception
     * @return bool
     */
    public function generate(): bool
    {
        try {
            $this->files[] = $this->createModel();

            $this->files[] = $this->createRequest();

            $this->files[] = $this->createController();

            $this->files[] = $this->createFrontTable();

            $this->files[] = $this->createFrontForm();

            $this->files[] = $this->createRoute();
        } catch (Exception $e) {
            $this->rollback();
            throw new FailedException($e->getMessage());
        }

        $this->files = [];

        return true;
    }

    /**
     * create route
     *
     * @throws FileNotFoundException
     * @return bool|string
     */
    public function createRoute(): bool|string
    {
        // 保存之前的 route 文件
        $route = new Route($this->gen['controller']);

        return $route->setModule($this->gen['module'])->create();
    }

    /**
     * create font
     *
     * @throws FileNotFoundException
     * @return bool|string|null
     */
    public function createFrontTable(): bool|string|null
    {
        $table = new FrontTable($this->gen['controller'], $this->gen['paginate'], (new Route($this->gen['controller']))->setModule($this->gen['module'])->getApiRute());

        return $table->setModule($this->gen['module'])->setStructures($this->structures)->create();
    }

    /**
     * create font
     *
     * @throws FileNotFoundException
     * @return bool|string|null
     */
    public function createFrontForm(): bool|string|null
    {
        $form = new FrontForm($this->gen['controller']);

        return $form->setModule($this->gen['module'])->setStructures($this->structures)->create();
    }


    /**
     * create model
     *
     * @throws FileNotFoundException
     * @return bool|string
     */
    protected function createModel(): bool|string
    {
        $model = new Model($this->gen['model'], $this->gen['schema'], $this->gen['module']);

        $this->modelName = $model->getModelName();

        return $model->setModule($this->gen['module'])->setStructures($this->structures)->create();
    }

    /**
     * create request
     *
     * @throws FileNotFoundException
     * @return bool|string
     */
    protected function createRequest(): bool|string
    {
        $request = new Request($this->gen['controller']);

        $file = $request->setStructures($this->structures)->setModule($this->gen['module'])->create();

        $this->requestName = $request->getRequestName();

        return $file;
    }

    /**
     * create controller
     *
     * @throws FileNotFoundException
     * @return bool|string
     */
    protected function createController(): bool|string
    {
        $controller = new Controller($this->gen['controller'], $this->modelName, $this->requestName);

        return $controller->setModule($this->gen['module'])->create();
    }

    /**
     * rollback
     *
     * @return void
     */
    protected function rollback(): void
    {
        // delete controller & model & migration file
        foreach ($this->files as $file) {
            unlink($file);
        }

        // 回填之前的 route 文件
    }


    /**
     * set params
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): Generator
    {
        $this->gen = $params['codeGen'];

        $this->structures = $params['structures'];

        return $this;
    }
}
