---
name: model
description: Generate Eloquent model for CatchAdmin module with full CatchModel features.
---

# Step 3: Generate Model

创建 Eloquent 模型，继承 `Catch\Base\CatchModel`。

## File Location

```
modules/{Module}/Models/{Model}.php
```

## Complete Template

```php
<?php

namespace Modules\{Module}\Models;

use Catch\Base\CatchModel as Model;
use Catch\Enums\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class {Model} extends Model
{
    protected $table = '{table}';

    protected $fillable = [
        'id',
        // business fields
        'creator_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * 搜索字段配置
     * Operators: like, =, in, between
     */
    public array $searchable = [
        'name' => 'like',
        'status' => '=',
        'category_id' => '=',
    ];

    /**
     * 列表查询字段
     */
    protected array $fields = [
        'id', 'name', 'status', 'created_at'
    ];

    /**
     * 表单提交字段
     */
    protected array $form = [
        'name', 'status'
    ];

    /**
     * 表单关联同步 (多对多)
     */
    protected array $formRelations = ['roles', 'tags'];

    /**
     * 排序字段
     */
    protected string $sortField = 'sort';

    /**
     * 降序排序
     */
    protected bool $sortDesc = true;

    /**
     * 树形结构 (parent_id)
     */
    protected string $parentIdColumn = 'parent_id';

    /**
     * 是否返回树形结构
     */
    protected bool $asTree = false;

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
```

---

## CatchModel 核心属性

| 属性 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `$fillable` | array | [] | 可填充字段 |
| `$searchable` | array | [] | 搜索字段及操作符 |
| `$fields` | array | ['*'] | 列表查询字段 |
| `$form` | array | [] | 表单提交字段 |
| `$formRelations` | array | [] | 表单关联同步 |
| `$sortField` | string | '' | 排序字段 |
| `$sortDesc` | bool | true | 是否降序 |
| `$parentIdColumn` | string | 'parent_id' | 父级字段 |
| `$asTree` | bool | false | 返回树形结构 |
| `$isPaginate` | bool | true | 是否分页 |
| `$dataRange` | bool | false | 数据权限 |
| `$isFillCreatorId` | bool | true | 自动填充创建人 |

---

## CatchModel CRUD 方法

### 列表查询

```php
// 基础列表 (自动分页、搜索、排序)
$this->model->getList();

// 自定义查询条件
$this->model->setBeforeGetList(function ($query) {
    return $query->where('type', 1);
})->getList();

// 禁用分页
$this->model->disablePaginate()->getList();

// 返回树形结构
$this->model->asTree()->getList();
```

### 创建记录

```php
// 创建并返回 ID
$this->model->storeBy($data);

// 创建新实例
$this->model->createBy($data);
```

### 更新记录

```php
// 更新单条
$this->model->updateBy($id, $data);

// 批量更新
$this->model->batchUpdate('id', [1, 2, 3], [
    'status' => [1, 2, 1],
]);
```

### 删除记录

```php
// 软删除
$this->model->deleteBy($id);

// 强制删除
$this->model->deleteBy($id, force: true);

// 批量删除
$this->model->deletesBy('1,2,3');
$this->model->deletesBy([1, 2, 3]);
```

### 其他操作

```php
// 查询单条
$this->model->firstBy($id);
$this->model->firstBy($value, 'field');

// 切换状态
$this->model->toggleBy($id);
$this->model->toggleBy($id, 'is_active');

// 恢复软删除
$this->model->restoreBy($id);
$this->model->restoreBy('1,2,3');
```

---

## 搜索操作符

```php
public array $searchable = [
    'name' => 'like',      // LIKE '%value%'
    'title' => '%like',    // LIKE '%value'
    'code' => 'like%',     // LIKE 'value%'
    'status' => '=',       // = value
    'type' => 'in',        // IN (values)
    'created_at' => 'between', // BETWEEN start AND end
    'price' => '>',        // > value
    'stock' => '>=',       // >= value
];
```

---

## 关联关系

### BelongsTo (多对一)

```php
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class, 'category_id');
}

public function creator(): BelongsTo
{
    return $this->belongsTo(\Modules\User\Models\User::class, 'creator_id');
}
```

### HasMany (一对多)

```php
public function items(): HasMany
{
    return $this->hasMany(OrderItem::class, 'order_id');
}
```

### BelongsToMany (多对多)

```php
// 需要在 $formRelations 中声明才能自动同步
protected array $formRelations = ['roles', 'tags'];

public function roles(): BelongsToMany
{
    return $this->belongsToMany(
        Role::class,
        'user_has_roles',  // pivot table
        'user_id',
        'role_id'
    );
}

public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class, 'product_tags');
}
```

### 树形关系 (自关联)

```php
protected string $parentIdColumn = 'parent_id';

public function parent(): BelongsTo
{
    return $this->belongsTo(self::class, 'parent_id');
}

public function children(): HasMany
{
    return $this->hasMany(self::class, 'parent_id');
}
```

---

## 属性访问器 & 修改器

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

// 密码加密
protected function password(): Attribute
{
    return new Attribute(
        set: fn ($value) => bcrypt($value),
    );
}

// 状态标签
protected function statusLabel(): Attribute
{
    return new Attribute(
        get: fn () => $this->status == 1 ? '启用' : '禁用',
    );
}

// JSON 字段
protected function options(): Attribute
{
    return new Attribute(
        get: fn ($value) => json_decode($value, true) ?? [],
        set: fn ($value) => json_encode($value),
    );
}
```

---

## 状态枚举

```php
use Catch\Enums\Status;

public function isEnabled(): bool
{
    return Status::Enable->assert($this->status);
}

public function isDisabled(): bool
{
    return Status::Disable->assert($this->status);
}
```

---

## 重写方法

### 自定义更新逻辑

```php
public function updateBy($id, array $data): mixed
{
    // 密码为空时不更新
    if (empty($data['password'])) {
        unset($data['password']);
    }

    return parent::updateBy($id, $data);
}
```

### 自定义删除逻辑

```php
public function deleteBy($id, bool $force = false, bool $softForce = false): ?bool
{
    return $this->transaction(function () use ($id) {
        // 删除关联数据
        $this->items()->where('order_id', $id)->delete();
        
        return parent::deleteBy($id);
    });
}
```

---

## 查询作用域

```php
use Illuminate\Database\Eloquent\Builder;

public function scopeActive(Builder $query): Builder
{
    return $query->where('status', 1);
}

public function scopeOfType(Builder $query, int $type): Builder
{
    return $query->where('type', $type);
}

// 使用
Model::active()->ofType(1)->get();
```

---

## 关联预加载

```php
// 在模型中定义默认预加载
protected $with = ['category'];

// 在控制器中
public function show($id): mixed
{
    return $this->model
        ->with(['category', 'tags'])
        ->firstBy($id);
}
```

---

## 外键字段自动检测规则

| 字段名 | 关联方法 | 关联模型 |
|--------|----------|----------|
| `category_id` | `category()` | `Category::class` |
| `user_id` | `user()` | `User::class` |
| `department_id` | `department()` | `Departments::class` |
| `parent_id` | `parent()` | `self::class` |
