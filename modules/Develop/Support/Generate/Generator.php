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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Develop\Models\SchemaFiles;
use Modules\Develop\Support\Generate\Create\Controller;
use Modules\Develop\Support\Generate\Create\Dynamic;
use Modules\Develop\Support\Generate\Create\FrontForm;
use Modules\Develop\Support\Generate\Create\FrontTable;
use Modules\Develop\Support\Generate\Create\Menu;
use Modules\Develop\Support\Generate\Create\Model;
use Modules\Develop\Support\Generate\Create\Request;
use Modules\Develop\Support\Generate\Create\Route;
use Modules\Develop\Support\Generate\Exception\MenuCreateFailException;

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

    protected array $structures;

    protected mixed $schemaId;

    protected array $files = [];

    /**
     * this model name from controller
     */
    protected string $modelName;

    /**
     * this request name for controller
     */
    protected ?string $requestName = null;

    protected ?string $originRouteContent = null;

    /**
     * generate
     *
     * @throws Exception
     */
    public function generate(): bool
    {
        try {
            $this->files['dynamic_path'] = $this->createDynamic();
            $this->files['model_path'] = $this->createModel();
            $this->files['request_path'] = $this->createRequest();
            $this->files['controller_path'] = $this->createController();
            $this->files['table_path'] = $this->createFrontTable();
            $this->files['form_path'] = $this->createFrontForm();
            $this->createRoute();
            // 生成菜单
            (new Menu($this->gen))->useDialogForm($this->gen['dialogForm'])->generate();
            // 保存文件内容
            $this->saveFiles($this->files);
        } catch (MenuCreateFailException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('代码生成错误'.$e->getMessage());
            $this->rollback();
            throw $e;
        } finally {
            $this->files = [];
        }

        return true;
    }

    /**
     * create route
     *
     * @throws FileNotFoundException
     */
    public function createRoute(): bool|string
    {
        // 保存之前的 route 文件
        $route = new Route($this->gen['controller']);

        $route = $route->setModule($this->gen['module']);

        // 保存原始的 route 文件内容
        $this->originRouteContent = $route->getOriginContent();

        return $route->create();
    }

    /**
     * create font
     *
     * @throws FileNotFoundException
     */
    public function createFrontTable(): bool|string|null
    {
        $apiString = (new Route($this->gen['controller']))->setModule($this->gen['module'])->getApiRoute();
        $table = new FrontTable(
            $this->gen['controller'],
            $this->gen['paginate'],
            $apiString,
            $this->gen['form'],
            $this->gen['dymaic'],
            $this->gen['dialogForm'],
            $this->gen['operations']
        );

        return $table->setModule($this->gen['module'])->setStructures($this->structures)->create();
    }

    public function createDynamic()
    {
        if ($this->gen['dymaic']) {
            $apiString = (new Route($this->gen['controller']))->setModule($this->gen['module'])->getApiRoute();

            $dynamic = new Dynamic(
                $this->gen['controller'],
                $this->structures,
                $this->gen['form'],
                $apiString,
                $this->gen['dialogForm']
            );

            return $dynamic->setModule($this->gen['module'])->create();
        }
    }

    /**
     * create font
     *
     * @throws FileNotFoundException
     */
    public function createFrontForm(): bool|string|null
    {
        // 无需创建 form
        if (! $this->gen['form']) {
            return false;
        }

        $apiString = (new Route($this->gen['controller']))->setModule($this->gen['module'])->getApiRoute();

        $form = new FrontForm(
            $this->gen['controller'],
            $this->gen['dymaic'],
            $this->gen['dialogForm'],
            $apiString
        );

        return $form->setModule($this->gen['module'])->setStructures($this->structures)->setTableName($this->gen['schema'])->create();
    }

    /**
     * create model
     *
     * @throws FileNotFoundException
     */
    protected function createModel(): bool|string
    {
        if (! $this->gen['model']) {
            throw new FailedException('模型名称不能为空');
        }

        $model = new Model($this->gen['model'], $this->gen['schema'], $this->gen['module'], $this->gen['relations']);

        $this->modelName = $model->getModelName();

        return $model->setModule($this->gen['module'])->setStructures($this->structures)->create();
    }

    /**
     * create request
     *
     * @throws FileNotFoundException
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
     */
    protected function createController(): bool|string
    {
        $controller = new Controller($this->gen['controller'], $this->modelName, $this->requestName, $this->gen['form'], $this->gen['dymaic'], $this->structures, $this->gen['operations']);

        return $controller->setModule($this->gen['module'])->create();
    }

    /**
     * rollback
     */
    protected function rollback(): void
    {
        // delete controller & model & migration file
        foreach ($this->files as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        // 回滚 route 文件
        if ($this->originRouteContent) {
            $route = new Route($this->gen['controller']);
            $route->setModule($this->gen['module'])->putOriginContent($this->originRouteContent);
        }
    }

    /**
     * 保存文件
     */
    protected function saveFiles($params): mixed
    {
        $schemaFiles = SchemaFiles::where('schema_id', $this->schemaId)->first();

        $schemaFilesModel = new SchemaFiles();
        $data = [];
        foreach ($params as $key => $filepath) {
            $fileKey = Str::of($key)->replace('path', 'file')->toString();
            if (file_exists($filepath)) {
                $data[$key] = $filepath;
                $data[$fileKey] = file_get_contents($filepath);
            } else {
                $data[$key] = '';
                $data[$fileKey] = '';
            }
        }

        if ($schemaFiles) {
            return $schemaFilesModel->updateBy($schemaFiles->id, $data);
        } else {
            $data['schema_id'] = $this->schemaId;

            return $schemaFilesModel->storeBy($data);
        }
    }

    /**
     * set params
     *
     * @return $this
     */
    public function setParams(array $params): Generator
    {
        $this->gen = $params['codeGen'];

        foreach ($params['structures'] as &$structure) {
            $structure['search'] = strlen($structure['search_op']) > 0;
        }

        $this->structures = $params['structures'];

        $this->schemaId = $params['schemaId'];

        return $this;
    }
}
