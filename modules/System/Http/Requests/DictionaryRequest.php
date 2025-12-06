<?php
namespace Modules\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DictionaryRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('system_dictionary')->where('deleted_at', 0)->ignore($this->get('id'))],
            'key' => ['required', 'alpha', Rule::unique('system_dictionary')->where('deleted_at', 0)->ignore($this->get('id'))],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '字典名称必填',
            'name.unique' => '字典名称已存在',
            'key.required' => '字典key必填',
            'key.alpha' => '字典key只能包含字母',
            'key.unique' => '字典key已存在',
        ];
    }
}
