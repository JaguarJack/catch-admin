<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Permissions\Models\Roles;

class UserRequest extends FormRequest
{
    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
              'required',
               Rule::unique('users')->where(function ($query) {
                   return $query->when($this->get('id'), function ($query){
                       $query->where('id', '<>', $this->get('id'));
                   })->where('deleted_at', 0);
               })
            ],
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
            'email.required' => '邮箱必须填写',

            'email.unique' => '邮箱已存在',
        ];
    }
}
