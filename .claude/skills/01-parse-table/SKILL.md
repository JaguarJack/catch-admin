---
name: parse-table
description: Parse table definition to extract module name, model name, table name, and field definitions. First step of CRUD generation.
---

# Step 1: Parse Table Definition

从用户输入的表定义中提取关键信息。

## Input Format

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

## 输入容错与默认推断

- 允许使用以下别名：`Table/表名/表`，`Module/模块`，`Model/模型`，`Fields/字段`。
- 允许省略 `Fields:` 标题，仅用 `-` 列表表示字段。
- 若只提供 `table`，则按命名规则推断 `{Module}` 与 `{Model}`。
- 若用户显式提供 `Module/Model`，优先使用用户输入（覆盖推断）。
- 缺少 `table` 时必须先向用户确认，不进行推断生成。

## 字段解析补充

- `required` 表示 `nullable = false`，`nullable` 表示可空；两者都未出现时默认不可空。
- 支持别名：`int/integer`、`bool/boolean`、`pk/primary key`、`fk/foreign key`。
- `foreign key -> xxx` 解析为 `foreignKey` 类型，默认 `index()`。

## 冲突处理优先级

1) 用户显式指定（Module/Model/table/字段规则）
2) 规则推断（命名规则与默认字段规则）
3) 兜底：要求用户澄清后再继续

## Output Variables

| Variable | Rule | Example |
|----------|------|---------|
| `{table}` | snake_case plural | `products` |
| `{Model}` | PascalCase singular | `Product` |
| `{Module}` | PascalCase | `Product` |
| `{module}` | snake_case | `product` |
| `{resources}` | kebab-case plural | `products` |
| `{resource}` | kebab-case singular | `product` |

## Field Type Parsing

| Input | Type | Length | Required | Default | FK Target |
|-------|------|--------|----------|---------|-----------|
| `string, 100` | string | 100 | no | null | - |
| `string, 100, required` | string | 100 | yes | null | - |
| `integer, default 0` | integer | - | no | 0 | - |
| `foreign key -> categories` | foreignKey | - | no | 0 | categories |
| `decimal 10,2` | decimal | 10,2 | no | null | - |
| `tinyint, default 1` | tinyint | - | no | 1 | - |
| `text, nullable` | text | - | no | null | - |

## Naming Conventions

- **Module**: 通常与 Model 同名，除非用户明确指定
- **Table**: 复数形式 (products, order_items)
- **Model**: 单数形式 (Product, OrderItem)
- **Route**: kebab-case (products, order-items)
