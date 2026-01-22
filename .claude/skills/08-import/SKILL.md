---
name: import
description: Generate Excel import class for CatchAdmin module.
---

# Step 8: Generate Import Class

创建数据导入类。

## File Location

```
modules/{Module}/Import/{Model}.php
```

## Template

```php
<?php

namespace Modules\{Module}\Import;

use Catch\Support\Excel\Import;
use Illuminate\Support\Collection;
use Modules\{Module}\Models\{Model};

class {Model} extends Import
{
    public function collection(Collection $rows): void
    {
        // Skip header row
        $rows->skip(1)->each(function ($row) {
            {Model}::create([
                'name' => $row[0],
                // map other columns
            ]);
        });
    }
}
```

## Column Mapping

Excel 列索引从 0 开始：
- Column A = `$row[0]`
- Column B = `$row[1]`
- Column C = `$row[2]`

## With Validation

```php
public function collection(Collection $rows): void
{
    $rows->skip(1)->each(function ($row, $index) {
        $data = [
            'name' => $row[0] ?? null,
            'email' => $row[1] ?? null,
        ];

        $validator = Validator::make($data, [
            'name' => 'required|string|max:100',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return; // Skip invalid row
        }

        {Model}::create($data);
    });
}
```

## Controller Usage

```php
public function import(Request $request, {Model}Import $import)
{
    return $import->import($request->file('file'));
}
```
