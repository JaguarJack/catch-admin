---
name: controller
description: Generate CRUD controller for CatchAdmin module.
---

# Step 4: Generate Controller

创建 CRUD 控制器。

## File Location

```
modules/{Module}/Http/Controllers/{Model}Controller.php
```

## Template

```php
<?php

namespace Modules\{Module}\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Illuminate\Http\Request;
use Modules\{Module}\Http\Requests\{Model}Request;
use Modules\{Module}\Models\{Model};
use Modules\{Module}\Import\{Model} as {Model}Import;

class {Model}Controller extends Controller
{
    public function __construct(
        protected readonly {Model} $model
    ) {}

    /**
     * List with pagination
     */
    public function index()
    {
        return $this->model->getList();
    }

    /**
     * Create
     */
    public function store({Model}Request $request)
    {
        return $this->model->storeBy($request->all());
    }

    /**
     * Show
     */
    public function show($id): mixed
    {
        return $this->model->firstBy($id);
    }

    /**
     * Update
     */
    public function update($id, {Model}Request $request): mixed
    {
        return $this->model->updateBy($id, $request->all());
    }

    /**
     * Delete
     */
    public function destroy($id)
    {
        return $this->model->deleteBy($id);
    }

    /**
     * Toggle status
     */
    public function enable($id)
    {
        return $this->model->toggleBy($id);
    }

    /**
     * Export
     */
    public function export(): mixed
    {
        return {Model}::query()
            ->select('id', 'name', 'created_at')
            ->get()
            ->download(['ID', 'Name', 'Created At']);
    }

    /**
     * Import
     */
    public function import(Request $request, {Model}Import $import)
    {
        return $import->import($request->file('file'));
    }
}
```

## CatchController Methods

| Method | Purpose |
|--------|---------|
| `getList()` | 分页列表 (使用 $searchable) |
| `storeBy()` | 创建记录 (使用 $form) |
| `firstBy()` | 获取单条 |
| `updateBy()` | 更新记录 |
| `deleteBy()` | 软删除 |
| `toggleBy()` | 切换状态 |
