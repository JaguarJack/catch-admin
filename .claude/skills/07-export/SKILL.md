---
name: export
description: Generate Excel export class for CatchAdmin module.
---

# Step 7: Generate Export Class

创建数据导出类。

## File Location

```
modules/{Module}/Export/{Model}.php
```

## Template

```php
<?php

namespace Modules\{Module}\Export;

use Catch\Support\Excel\Export;

class {Model} extends Export
{
    protected array $header = [
        'ID', 'Name', 'Created At'
    ];

    public function array(): array
    {
        return \Modules\{Module}\Models\{Model}::query()
            ->select('id', 'name', 'created_at')
            ->get()
            ->toArray();
    }
}
```

## Header Mapping

| Field | Header |
|-------|--------|
| id | ID |
| name | Name |
| email | Email |
| status | Status |
| created_at | Created At |

## With Relationships

```php
public function array(): array
{
    return {Model}::query()
        ->with('category')
        ->get()
        ->map(fn ($item) => [
            $item->id,
            $item->name,
            $item->category?->name ?? '-',
            $item->created_at,
        ])
        ->toArray();
}
```

## Controller Usage

```php
public function export(): mixed
{
    return {Model}::query()
        ->select('id', 'name', 'created_at')
        ->get()
        ->download(['ID', 'Name', 'Created At']);
}
```
