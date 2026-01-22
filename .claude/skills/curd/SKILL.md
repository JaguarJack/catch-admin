---
name: curd
description: Generates complete CRUD module from database table definition. Orchestrates 9 sub-skills. Use when user says "create CRUD", "generate module from table", or provides a table structure.
globs:
  - "modules/**/*.php"
  - "web/src/views/**/*.vue"
---

# CatchAdmin CRUD Generator

**一键生成完整 CRUD 模块** - 从数据表定义到前后端完整实现。

## Sub-Skills (9 个子 Skills)

| Step | Skill | 生成文件 |
|------|-------|----------|
| 1 | `01-parse-table` | - (解析输入) |
| 2 | `02-migration` | `database/migrations/*.php` |
| 3 | `03-model` | `Models/{Model}.php` |
| 4 | `04-controller` | `Http/Controllers/{Model}Controller.php` |
| 5 | `05-request` | `Http/Requests/{Model}Request.php` |
| 6 | `06-routes` | `routes/route.php` |
| 7 | `07-export` | `Export/{Model}.php` |
| 8 | `08-import` | `Import/{Model}.php` |
| 9 | `09-vue-pages` | `index.vue` + `create.vue` |

---

## Input: Table Definition

```
Table: products
Fields:
- id (primary key)
- name (string, 100, required)
- category_id (foreign key -> categories)
- description (text, nullable)
- price (decimal 10,2, required)
- stock (integer, default 0)
- status (tinyint, default 1)
```

---

## Output: Complete Module Structure

```
modules/{Module}/
├── Http/
│   ├── Controllers/{Model}Controller.php
│   └── Requests/{Model}Request.php
├── Models/{Model}.php
├── Export/{Model}.php
├── Import/{Model}.php
├── database/migrations/*.php
└── routes/route.php

web/src/views/{module}/
├── index.vue
└── create.vue
```

---

## Pre-checks (执行前)

- **模块存在性**：若 `modules/{Module}` 不存在，先询问是否创建新模块。
- **文件冲突**：目标文件已存在时，先询问覆盖或增量修改。
- **路由/权限前缀**：检查 `modules/{Module}/routes/route.php` 是否已有相同资源前缀。
- **依赖/配置**：新增依赖或改动 `config/` 前必须先询问。

---

## Output Contract (输出契约)

- 必须生成 9 步完整产物（迁移/模型/控制器/请求/路由/导入导出/前端页）。
- 所有路径、类名、命名空间与 `{Module}/{Model}/{table}` 一致。
- 前端 `catch-table` 的 `api` 与后端路由前缀保持一致。

---

## Consistency Validation (生成后校验)

- 迁移包含 CatchAdmin 标准字段：`creator_id/created_at/updated_at/deleted_at` + 索引。
- Model/Request 与迁移字段一致（类型/必填/默认值）。
- Route API 前缀与前端 `api`/`permission` 前缀一致。

---

## Fallback Rules (失败兜底)

- 输入缺失关键字段（如 table/fields）时必须先澄清，停止生成。
- 复杂关系或非标准业务逻辑需用户确认后再生成。

---

## Workflow

### Step 1: Parse Table Definition → `01-parse-table`

Extract from user input:
- Table name (snake_case plural)
- Model name (PascalCase singular)
- Module name (PascalCase)
- Fields with types, constraints, and relationships

### Step 2: Generate Migration → `02-migration`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{table}', function (Blueprint $table) {
            $table->id();
            
            // Generated from field definitions
            $table->string('name', 100);
            $table->unsignedBigInteger('category_id')->default(0)->index();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->string('image', 255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('sort')->default(0);
            
            // CatchAdmin standard fields
            $table->unsignedInteger('creator_id')->default(0);
            $table->unsignedInteger('created_at')->default(0);
            $table->unsignedInteger('updated_at')->default(0);
            $table->unsignedInteger('deleted_at')->default(0);
            
            $table->index(['status', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{table}');
    }
};
```

### Step 3: Generate Model → `03-model`

```php
<?php

namespace Modules\{Module}\Models;

use Catch\Base\CatchModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class {Model} extends Model
{
    protected $table = '{table}';

    protected $fillable = [
        'id', 'name', 'category_id', 'description', 'price', 'stock',
        'image', 'status', 'sort', 'creator_id', 'created_at', 'updated_at', 'deleted_at',
    ];

    public array $searchable = [
        'name' => 'like',
        'category_id' => '=',
        'status' => '=',
    ];

    protected array $fields = [
        'id', 'name', 'category_id', 'price', 'stock', 'status', 'created_at'
    ];

    protected array $form = [
        'name', 'category_id', 'description', 'price', 'stock', 'image', 'status', 'sort'
    ];

    // Relationships (auto-detected from foreign keys)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
```

### Step 4: Generate Controller → `04-controller`

```php
<?php

namespace Modules\{Module}\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Modules\{Module}\Http\Requests\{Model}Request;
use Modules\{Module}\Models\{Model};
use Modules\{Module}\Import\{Model} as {Model}Import;
use Illuminate\Http\Request;

class {Model}Controller extends Controller
{
    public function __construct(
        protected readonly {Model} $model
    ) {}

    public function index()
    {
        return $this->model->getList();
    }

    public function store({Model}Request $request)
    {
        return $this->model->storeBy($request->all());
    }

    public function show($id): mixed
    {
        return $this->model->firstBy($id);
    }

    public function update($id, {Model}Request $request): mixed
    {
        return $this->model->updateBy($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->model->deleteBy($id);
    }

    public function enable($id)
    {
        return $this->model->toggleBy($id);
    }

    public function export(): mixed
    {
        return {Model}::query()
            ->select('id', 'name', 'price', 'stock', 'created_at')
            ->get()
            ->download(['ID', 'Name', 'Price', 'Stock', 'Created At']);
    }

    public function import(Request $request, {Model}Import $import)
    {
        return $import->import($request->file('file'));
    }
}
```

### Step 5: Generate Request → `05-request`

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
            // Auto-generated from field definitions
            'name' => 'required|string|max:100',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'sometimes|integer|in:1,2',
            'sort' => 'sometimes|integer|min:0',
        ];
    }
}
```

### Step 6: Generate Routes → `06-routes`

```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\{Module}\Http\Controllers\{Model}Controller;

Route::apiResource('{resources}', {Model}Controller::class);
Route::put('{resources}/enable/{id}', [{Model}Controller::class, 'enable']);
Route::get('{resource}/export', [{Model}Controller::class, 'export']);
Route::post('{resource}/import', [{Model}Controller::class, 'import']);
```

### Step 7: Generate Export → `07-export`

```php
<?php

namespace Modules\{Module}\Export;

use Catch\Support\Excel\Export;

class {Model} extends Export
{
    protected array $header = ['ID', 'Name', 'Price', 'Stock', 'Created At'];

    public function array(): array
    {
        return \Modules\{Module}\Models\{Model}::query()
            ->select('id', 'name', 'price', 'stock', 'created_at')
            ->get()
            ->toArray();
    }
}
```

### Step 8: Generate Import → `08-import`

```php
<?php

namespace Modules\{Module}\Import;

use Catch\Support\Excel\Import;
use Illuminate\Support\Collection;
use Modules\{Module}\Models\{Model};

class {Model}Import extends Import
{
    public function collection(Collection $rows): void
    {
        $rows->skip(1)->each(function ($row) {
            {Model}::create([
                'name' => $row[0],
                'price' => $row[1],
                'stock' => $row[2],
            ]);
        });
    }
}
```

### Step 9: Generate Vue Pages → `09-vue-pages`

**index.vue:**
```vue
<template>
  <div>
    <catch-table
      :columns="columns"
      api="{module}/{resources}"
      :exports="true"
      :trash="true"
      permission="{module}.{model}"
      exportUrl="/{module}/export"
      importUrl="/{module}/import"
      :search-form="searchForm"
    >
      <template #dialog="row">
        <Create :primary="row?.id" api="{module}/{resources}" />
      </template>
    </catch-table>
  </div>
</template>

<script lang="ts" setup>
import Create from './create.vue'

const columns = [
  { type: 'selection' },
  { label: 'ID', prop: 'id', width: 80 },
  { label: 'Name', prop: 'name' },
  { label: 'Category', prop: 'category.name' },
  { label: 'Price', prop: 'price', width: 100 },
  { label: 'Stock', prop: 'stock', width: 80 },
  { label: 'Status', prop: 'status', switch: true, width: 80 },
  { label: 'Created At', prop: 'created_at', width: 180 },
  { type: 'operate', label: 'Actions', width: 150, update: true, destroy: true }
]

const searchForm = [
  { type: 'input', label: 'Name', name: 'name' },
  { type: 'select', label: 'Category', name: 'category_id', api: 'categories' },
  { type: 'select', label: 'Status', name: 'status', api: 'status' }
]
</script>
```

**create.vue:**
```vue
<template>
  <el-form :model="formData" label-width="100px" ref="form" v-loading="loading">
    <el-form-item label="Name" prop="name" :rules="[{ required: true, message: 'Required' }]">
      <el-input v-model="formData.name" maxlength="100" />
    </el-form-item>
    <el-form-item label="Category" prop="category_id" :rules="[{ required: true, message: 'Required' }]">
      <el-select v-model="formData.category_id" class="w-full">
        <el-option v-for="item in categories" :key="item.id" :label="item.name" :value="item.id" />
      </el-select>
    </el-form-item>
    <el-form-item label="Price" prop="price" :rules="[{ required: true, message: 'Required' }]">
      <el-input-number v-model="formData.price" :precision="2" :min="0" class="w-full" />
    </el-form-item>
    <el-form-item label="Stock" prop="stock">
      <el-input-number v-model="formData.stock" :min="0" class="w-full" />
    </el-form-item>
    <el-form-item label="Description" prop="description">
      <el-input v-model="formData.description" type="textarea" :rows="3" />
    </el-form-item>
    <el-form-item label="Status" prop="status">
      <el-radio-group v-model="formData.status">
        <el-radio :value="1">Active</el-radio>
        <el-radio :value="2">Inactive</el-radio>
      </el-radio-group>
    </el-form-item>
    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">Submit</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { inject, onMounted, ref } from 'vue'
import { useCreate } from '@/composables/curd/useCreate'
import { useShow } from '@/composables/curd/useShow'
import http from '@/support/http'

const props = defineProps<{ primary?: string | number; api: string }>()
const { formData, form, loading, submitForm, close } = useCreate(props.api, props.primary)
if (props.primary) useShow(props.api, props.primary, formData)

const closeDialog = inject('closeDialog')
const categories = ref([])

onMounted(async () => {
  close(() => closeDialog?.())
  const res = await http.get('categories')
  categories.value = res.data.data
})
</script>
```

---

## Field Type Mapping

| Input Type | Migration | Model | Validation | Vue Component |
|------------|-----------|-------|------------|---------------|
| string(n) | `string('x', n)` | fillable | `string|max:n` | `el-input` |
| text | `text('x')` | fillable | `string` | `el-input type="textarea"` |
| integer | `unsignedInteger('x')` | fillable | `integer|min:0` | `el-input-number` |
| decimal(m,n) | `decimal('x', m, n)` | fillable | `numeric` | `el-input-number :precision="n"` |
| boolean | `tinyInteger('x')` | fillable | `integer|in:1,2` | `el-switch` or `el-radio-group` |
| foreign_key | `unsignedBigInteger('x')` | belongsTo | `exists:table,id` | `el-select` |
| date | `date('x')` | fillable | `date` | `el-date-picker` |
| datetime | `dateTime('x')` | fillable | `date` | `el-date-picker type="datetime"` |
| json | `json('x')` | `$casts['x'] = 'array'` | `array` | Custom |

---

## Execution Checklist

When generating a complete CRUD:

- [ ] Step 1: Parse table definition
- [ ] Step 2: Create migration file
- [ ] Step 3: Create model
- [ ] Step 4: Create controller
- [ ] Step 5: Create request validation
- [ ] Step 6: Create routes
- [ ] Step 7: Create export class
- [ ] Step 8: Create import class
- [ ] Step 9: Create Vue pages
- [ ] Run `php artisan migrate`

---

## Boundaries

- ✅ **Always**: Generate all files, follow CatchAdmin conventions
- ⚠️ **Ask first**: Complex relationships, custom business logic
- 🚫 **Never**: Skip validation, ignore foreign key constraints
