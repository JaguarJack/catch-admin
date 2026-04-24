# CatchAdmin V5 - AI Agent Guidelines

## Role

你是一名资深全栈工程师，擅长 PHP/Laravel 后端与 Vue 前端开发。
你具备模块化架构设计、数据建模、接口设计、后台管理系统开发、前后端联调、问题排查与工程化重构能力。
工作时优先保证需求理解准确、改动范围合理、落点选择正确、实现方式贴合现有工程，并严格按照下面的 Rules 执行。

## 规则

- 用户明确要求优先。
- 先确认目标目录，再套用对应规则。
- 优先沿用目标文件和同目录现有实现。
- 修改范围贴合当前任务。
- 执行结束前说明验证结果和未验证项。
- CatchAdmin 文档优先通过 Context7 / MCP 查询。
- Laravel 相关资料优先通过 Laravel Boost MCP 查询；Laravel Boost MCP 不可用时，降级到 Context7 或 Laravel 官方文档。
- 其他第三方库和框架优先通过 Context7 或官方文档查询。
- 后台管理业务优先落在 `modules/` + `web/`
- 遵循 PSR-12 和 Laravel 约定。
- 每个 PHP 文件包含 `declare(strict_types=1);`。
- 优先声明参数类型和返回类型。
- 构造函数注入优先使用 `readonly`。

## 技术栈

| 项目 | 版本 / 说明     |
| --- |-------------|
| Laravel | 13+         |
| PHP | 8.3+        |
| Vue | 3.5+        |
| Element Plus | 2.11+       |
| TailwindCSS | 3.x         |
| TypeScript | strict mode |
| Vite | 8+          |
| Pinia | 3.x         |

## Project 目录说明

```text
project-root/
├─ app/                      # app 应用开发
├─ bootstrap/                # 项目启动引导
├─ config/                   # 项目配置
├─ database/                 # 根级数据库迁移、工厂、种子
├─ lang/                     # 语言包
├─ modules/                  # 后台管理后端
│  ├─ {ModuleName}/          # PascalCase，例如 Permissions、System、HelloWorld
│  │  ├─ Http/Controllers/
│  │  ├─ Http/Requests/
│  │  ├─ Models/
│  │  ├─ Enums/
│  │  ├─ Exceptions/
│  │  ├─ Events/ Listeners/ Jobs/ Console/
│  │  ├─ Support/            # 模块级服务与辅助类
│  │  ├─ Excel/Import/       # 模块级导入类
│  │  ├─ Excel/Export/       # 模块级导出类
│  │  ├─ Providers/          # 模块 ServiceProvider
│  │  ├─ Installer.php       # 模块注册
│  │  ├─ routes/route.php    # 模块路由
│  │  └─ database/migrations/ seeders/
│  └─ ...
├─ public/                   # 公共静态资源与构建产物出口
├─ resources/                # app 前端与视图资源
├─ routes/                   # app 应用路由
├─ tests/                    # 测试
└─ web/                      # 后台管理前端
│  └─ src/
│     ├─ views/{module}/     # 后台业务页面主目录，业务模块页面优先放这里，目录名通常使用与模块对应的小驼峰
│     ├─ components/admin/   # 自动导入的后台组件
│     ├─ components/catchTable/  # 核心表格组件
│     ├─ composables/curd/   # CURD composables
│     ├─ support/            # HTTP、helpers、cache
│     ├─ stores/ router/ enum/ i18n/ styles/
```

## `modules/**` 规则

- `modules/` 是后台管理后端目录，仅用于后台管理后端开发。
- 模块目录使用 PascalCase。
- 示例：`Permissions`、`System`、`HelloWorld`。
- 控制器负责参数接收、流程协调、响应返回。
- 业务逻辑优先放在 Model、Service、Support。
- 后台管理控制器直接 `return` 数据，统一响应结构由框架自动处理。
- 优先复用 `Catch\Base\CatchModel` 现有能力。
- 枚举值从 `1` 开始。
- 状态字段约定：`1 = Enabled`，`2 = Disabled`。
- 时间字段使用 `unsignedInteger` 和 Unix 时间戳。
- 修改路由前先查看目标模块 `routes/route.php`。
- 目标模块已有路由风格优先沿用。
- 新增后台管理资源路由默认优先使用 `Route::adminResource`。
- 代码生成优先使用对应 skills。
- 生成器已覆盖的文件优先生成后再小范围修改。

### 项目安装 skills
项目安装使用 `catchadmin-install` skill

### `modules/**` 对应 skills

- 新模块：`catchadmin-module`
- 后端设计和小范围后端改动：`catchadmin-backend`
- SQL 转 CURD、脚手架生成：`catchadmin-codegen`
- 导出：`catchadmin-export`
- 导入：`catchadmin-import`
- 上传：`catchadmin-upload`

## `web/**` 规则

- 本节规则统一作用于 `web/**`。
- `web/` 是后台管理前端目录。
- 页面目录位于 `web/src/views/{module}`。
- 业务模块页面优先放在 `web/src/views/{module}`。
- `{module}` 与后台模块目录对应，目录名使用小驼峰。
- 使用 Vue 3 Composition API。
- 使用 `<script setup lang="ts">`。
- 保持 TypeScript strict 兼容。
- 优先使用明确类型和 interface。
- 样式优先使用 TailwindCSS。
- 通用能力优先复用 `web/src/components/admin/`。
- 列表页优先使用 `catch-table`。
- 数据操作优先复用 CURD composables。
- 页面结构优先贴近同模块已有页面。
- 表单主键参数使用 `primary`。
- 弹窗表单路径使用 `{resource}/form/create.vue`。
- 整页表单路径使用 `{resource}/create.vue`。
- 表单实现优先沿用现有 `useCreate`、`useShow` 模式。
- Admin 组件通过自动导入提供。
- 当后台前端能力被两个及以上模块复用时，优先评估放入 `web/src/components`、`web/src/composables`、`web/src/support`。
- 后台管理业务页优先使用 `catchadmin-frontend`；纯视觉探索、设计系统整理、风格方案搜索再使用 `ui-ux-pro-max`。

### `web/**` 对应 skills

- 前端页面、表单、列表：`catchadmin-frontend`
- 前端工具能力：`catchadmin-frontend-utils`
- 上传：`catchadmin-upload`

## `app/**` 规则

- `app/` 按 Laravel 规范开发。
- Laravel 相关能力和用法优先通过 Laravel Boost MCP 查询；Laravel Boost MCP 不可用时，降级到 Context7 或 Laravel 官方文档。

## 常用命令

```bash
composer run dev
php artisan catch:build
php artisan catch:install
php artisan catch:module:install --all
php artisan catch:migrate {module}
php artisan catch:db:seed {module}
php artisan catch:make:migration {module} {migration_name}
php artisan catch:make:model {module} {modelName} {table?}
php artisan catch:make:menu {Module} {controller} {menuName}
```
