---
name: migration
description: Generate database migration file for CatchAdmin module.
---

# Step 2: Generate Migration

创建数据库迁移文件。

## File Location

```
modules/{Module}/database/migrations/{timestamp}_create_{table}_table.php
```

## Template

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
            
            // Business fields (generated from definition)
            
            // CatchAdmin standard fields
            $table->unsignedInteger('creator_id')->default(0);
            $table->unsignedInteger('created_at')->default(0);
            $table->unsignedInteger('updated_at')->default(0);
            $table->unsignedInteger('deleted_at')->default(0);
            
            // Indexes
            $table->index(['status', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{table}');
    }
};
```

## Field Type Mapping

| Type | Migration Code |
|------|----------------|
| string(n) | `$table->string('name', n)` |
| text | `$table->text('description')->nullable()` |
| integer | `$table->unsignedInteger('count')->default(0)` |
| decimal(m,n) | `$table->decimal('price', m, n)` |
| tinyint | `$table->tinyInteger('status')->default(1)` |
| foreign_key | `$table->unsignedBigInteger('category_id')->default(0)->index()` |
| date | `$table->date('birth_date')->nullable()` |
| datetime | `$table->dateTime('published_at')->nullable()` |
| json | `$table->json('options')->nullable()` |

## Important

- CatchAdmin 使用 **unsigned integer** 作为时间戳，不使用 Laravel 默认的 `timestamps()`
- 软删除使用 `deleted_at` unsigned integer，不使用 `softDeletes()`
- 外键字段需要添加 `index()`
