<?php

namespace Modules\Develop\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\Develop\Models\Schemas;
use Modules\Develop\Support\Generate\Generator;

/**
 * @group 开发模块
 *
 * @subgroup 代码生成
 * @subgroupDescription CatchAdmin 代码生成
 */
class GenerateController extends Controller
{
    /**
     * 代码生成
     *
     * @bodyParam schemaId int required schema id
     *
     * @bodyParam codeGen object required 代码生成的参数对象
     * @bodyParam codeGen.module string required 模块名称
     * @bodyParam codeGen.controller string required 控制器名称
     * @bodyParam codeGen.model string required 模型名称
     * @bodyParam codeGen.paginate boolean 列表是否需要分页
     * @bodyParam codeGen.schema string 表名
     * @bodyParam codeGen.form boolean 是否开启表单
     * @bodyParam codeGen.menu string 菜单名称(填写则生成对应菜单，不填则不生成)
     *
     * @bodyParam structures object[] required
     * @bodyParam structures[].field string 字段名称: id
     * @bodyParam structures[].label string 表单label名称
     * @bodyParam structures[].form_component string 字段对应的表单组件 如:input
     * @bodyParam structures[].list boolean 是否在列表展示
     * @bodyParam structures[].form boolean 是否在表单展示
     * @bodyParam structures[].search boolean 是否在搜索表单展示
     * @bodyParam structures[].search_op string 搜索操作符 如:like
     * @bodyParam structures[].validates string[] Laravel 表单验证规则
     * @bodyParam structures[].dictionary string 字典
     *
     * @responseField data boolean 响应结果
     *
     * @param  Request  $request
     * @param  Generator  $generator
     * @return bool
     * @throws Exception
     */
    public function index(Request $request, Generator $generator)
    {
        $params = $request->all();

        Schemas::query()->where('id', $params['schemaId'])->update([
            'generate_params' => $params,
        ]);

        return $generator->setParams($request->all())->generate();
    }
}
