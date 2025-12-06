<?php
namespace Modules\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DictionaryValueRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules()
    {
        return [
            'label' => [
                'required',
                Rule::unique('system_dictionary_values')
                    ->where('dic_id', $this->get('dic_id'))
                    ->where('deleted_at', 0)
                    ->ignore($this->get('id'))
            ],
            'key' => [
                'required',
                'alpha_dash:ascii',
                Rule::unique('system_dictionary_values')
                    ->where('dic_id', $this->get('dic_id'))
                    ->where('deleted_at', 0)
                    ->ignore($this->get('id'))
            ],
            'value' => [
                'required',
                Rule::unique('system_dictionary_values')
                    ->where('dic_id', $this->get('dic_id'))
                    ->where('deleted_at', 0)
                    ->ignore($this->get('id'))
            ],
        ];
    }

    public function messages()
    {
        return [
            'label.required' => '字典值名称必填',
            'label.unique' => '字典值名称已存在',
            'key.required' => '字典值键名必填',
            'key.alpha_dash' => '字典值键名只能包含字母、数字和下划线',
            'key.unique' => '字典值键名已存在',
            'value.required' => '字典键值必填',
            'value.unique' => '字典键值已存在',
        ];
    }
}
