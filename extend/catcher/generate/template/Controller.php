<?php
namespace catcher\generate\template;

use catcher\base\CatchController;

class Controller
{
    use Content;

    /**
     * use
     *
     * @time 2020年04月27日
     * @return string
     */
    public function uses()
    {
        return <<<TMP
use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
{USE}


TMP;

    }

    /**
     * construct
     *
     * @time 2020年04月27日
     * @param $model
     * @return string
     */
    public function construct($model)
    {
        return <<<TMP
protected \$model;
    
    public function __construct({$model} \$model)
    {
        \$this->model = \$model;
    }
    
    
TMP;

    }

    public function createClass($class)
    {
        return <<<TMP
class {$class} extends CatchController
{
    {CONTENT}
}
TMP;

    }
    /**
     * list template
     *
     * @time 2020年04月24日
     * @return string
     */
    public function index()
    {
        return <<<TMP
{$this->controllerFunctionComment('列表', '')}
    public function index()
    {
        return CatchResponse::paginate(\$this->model->getList());
    }
    
    
TMP;
    }

    /**
     * create template
     *
     * @time 2020年04月24日
     * @return string
     */
    public function create()
    {
        return <<<TMP
{$this->controllerFunctionComment('单页')}
    public function create()
    {
        //
    }
    
    
TMP;

    }

    /**
     * save template
     *
     * @time 2020年04月24日
     * @param $createRequest
     * @return string
     */
    public function save($createRequest = '')
    {
        $request = $createRequest ? 'CreateRequest' : 'Request';

        return <<<TMP
{$this->controllerFunctionComment('保存', 'Request ' . $request)}
    public function save({$request} \$request)
    {
        return CatchResponse::success(\$this->model->storeBy(\$request->post()));
    }
    
    
TMP;
    }

    /**
     * read template
     *
     * @time 2020年04月24日
     * @return string
     */
    public function read()
    {
        return <<<TMP
{$this->controllerFunctionComment('读取', '$id')}
    public function read(\$id)
    {
       return CatchResponse::success(\$this->model->findBy(\$id)); 
    }
    
    
TMP;
    }

    /**
     * edit template
     *
     * @time 2020年04月24日
     * @return string
     */
    public function edit()
    {
        return <<<TMP
{$this->controllerFunctionComment('编辑', '\$id')}
    public function edit(\$id)
    {
        //
    }
    
    
TMP;
    }

    /**
     * update template
     *
     * @time 2020年04月24日
     * @param $updateRequest
     * @return string
     */
    public function update($updateRequest = '')
    {
        $updateRequest = ($updateRequest ? 'UpdateRequest' : 'Request') . ' $request';

        return <<<TMP
{$this->controllerFunctionComment('更新', $updateRequest)}
    public function update({$updateRequest}, \$id)
    {
        return CatchResponse::success(\$this->model->updateBy(\$id, \$request->post()));
    }
    
    
TMP;
    }

    /**
     * delete template
     *
     * @time 2020年04月24日
     * @return string
     */
    public function delete()
    {
        return <<<TMP
{$this->controllerFunctionComment('删除', '$id')}
    public function delete(\$id)
    {
        return CatchResponse::success(\$this->model->deleteBy(\$id));
    }
    
    
TMP;
    }

    /**
     * 其他方法
     *
     * @time 2020年04月27日
     * @param $function
     * @param string $method
     * @return string
     */
    public function otherFunction($function, $method = 'get')
    {
        $params = $method === 'delete' ? '$id' : 'Request $request';

        return <<<TMP
{$this->controllerFunctionComment('', $params)}
    public function {$function}({$params})
    {             
       // todo 
    }
    
    
TMP;
    }

    /**
     * 控制器方法注释
     *
     * @time 2020年04月24日
     * @param $des
     * @param $params
     * @return string
     */
    protected function controllerFunctionComment($des, $params = '')
    {
        $now = date('Y/m/d H:i', time());

        $params = $params ? '@param ' . $params : '';

        return <<<TMP
/**
     * {$des}
     *
     * @time {$now}
     * {$params} 
     * @return \\think\\Response
     */
TMP;
    }

}