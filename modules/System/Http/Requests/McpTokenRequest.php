<?php

declare(strict_types=1);

namespace Modules\System\Http\Requests;

use Catch\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Models\User;

class McpTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', Rule::exists(User::class, 'id')->where(fn ($query) => $query->where('status', Status::Enable->value)->where('deleted_at', 0))],
            'name' => ['required', 'string', 'max:100'],
            'expires_at' => ['required', 'integer', 'gt:' . time(), 'max:4294967295'],
        ];
    }

    public function attributes(): array
    {
        return ['user_id' => '绑定用户', 'name' => '令牌名称', 'expires_at' => '有效期'];
    }
}
