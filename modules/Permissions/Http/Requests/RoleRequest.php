<?php

namespace Modules\Permissions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Permissions\Models\RolesModel;

class RoleRequest extends FormRequest
{
    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'role_name' => sprintf('required|unique:%s,%s,%s', RolesModel::class, 'role_name', $this->get('id')),

            'identify' => sprintf('required|alpha|unique:%s,%s,%s', RolesModel::class, 'role_name', $this->get('id')),
        ];
    }


    /**
     * messages
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'role_name.required' => '角色名称必须填写',

            'role_name.unique' => '角色名称已存在',

            'identify.required' => '角色标识必须填写',

            'identify.alpha' => '角色名称只允许字母组成',

            'identify.unique' => '角色标识已存在',
        ];
    }
}
