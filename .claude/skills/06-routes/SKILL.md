---
name: routes
description: Generate route configuration for CatchAdmin module.
---

# Step 6: Generate Routes

创建路由配置。

## File Location

```
modules/{Module}/routes/route.php
```

## Template

```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\{Module}\Http\Controllers\{Model}Controller;

// CRUD routes
Route::apiResource('{resources}', {Model}Controller::class);

// Toggle status
Route::put('{resources}/enable/{id}', [{Model}Controller::class, 'enable']);

// Export
Route::get('{resource}/export', [{Model}Controller::class, 'export']);

// Import
Route::post('{resource}/import', [{Model}Controller::class, 'import']);

// Restore from trash
Route::put('{resources}/restore/{id}', [{Model}Controller::class, 'restore']);
```

## Route Naming

| Method | URI | Action |
|--------|-----|--------|
| GET | `/{resources}` | index |
| POST | `/{resources}` | store |
| GET | `/{resources}/{id}` | show |
| PUT | `/{resources}/{id}` | update |
| DELETE | `/{resources}/{id}` | destroy |
| PUT | `/{resources}/enable/{id}` | enable |
| GET | `/{resource}/export` | export |
| POST | `/{resource}/import` | import |

## Permission Middleware

路由自动被 `PermissionGate` 中间件保护。

跳过权限检查：
```php
Route::get('public/data', [Controller::class, 'data'])
    ->withoutMiddleware([PermissionGate::class]);
```
