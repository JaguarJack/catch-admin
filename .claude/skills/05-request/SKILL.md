---
name: request
description: Generate form request validation for CatchAdmin module.
---

# Step 5: Generate Request Validation

创建表单验证类。

## File Location

```
modules/{Module}/Http/Requests/{Model}Request.php
```

## Template

```php
<?php

namespace Modules\{Module}\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {Model}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Generated from field definitions
        ];
    }

    public function messages(): array
    {
        return [
            // Custom messages
        ];
    }
}
```

## Validation Rules Mapping

| Field Type | Validation Rule |
|------------|-----------------|
| string(n), required | `'required\|string\|max:n'` |
| string(n), nullable | `'nullable\|string\|max:n'` |
| integer | `'sometimes\|integer\|min:0'` |
| decimal | `'required\|numeric\|min:0'` |
| tinyint (status) | `'sometimes\|integer\|in:1,2'` |
| foreign_key | `'required\|integer\|exists:table,id'` |
| text | `'nullable\|string'` |
| email | `'required\|email\|max:100'` |
| date | `'nullable\|date'` |

## Unique Validation

对于更新操作，需要排除当前记录：

```php
public function rules(): array
{
    $id = $this->route('id');
    
    return [
        'email' => "required|email|unique:{table},email,{$id}",
    ];
}
```
