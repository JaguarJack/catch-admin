---
name: vue-pages
description: Generate Vue frontend pages using catch-table for CatchAdmin module with full component features.
---

# Step 9: Generate Vue Pages

创建前端页面，使用 `catch-table` 组件。

## File Locations

```
web/src/views/{module}/
├── index.vue    # List page
└── create.vue   # Form component
```

---

## catch-table 完整 Props

| Prop | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `api` | string | null | API 接口路径 |
| `columns` | Column[] | [] | 表格列配置 |
| `search-form` | SItem[] | [] | 搜索表单配置 |
| `permission` | string | null | 权限前缀 |
| `height` | string/number | 'auto' | 表格高度 |
| `border` | boolean | true | 是否显示边框 |
| `size` | string | 'default' | 尺寸: large/default/small |
| `row-key` | string | '' | 行数据 Key (树形必填) |
| `empty-text` | string | '暂无数据' | 空数据文本 |
| `pagination` | boolean | true | 是否显示分页 |
| `limit` | number | 10 | 每页数量 |
| `limits` | number[] | [10,20,30,40,50] | 可选每页数量 |
| `operation` | boolean | true | 是否显示新增按钮 |
| `show-tools` | boolean | true | 是否显示工具栏 |
| `show-header` | boolean | true | 是否显示表头工具 |
| `exports` | boolean | false | 是否显示导出 |
| `export-url` | string | '' | 导出接口 |
| `import-url` | string | '' | 导入接口 |
| `trash` | boolean | false | 是否启用回收站 |
| `multi-del-show` | boolean | true | 是否显示批量删除 |
| `primary-name` | string | 'id' | 主键字段名 |
| `default-params` | object | {} | 默认查询参数 |
| `destroy-confirm` | string | '确定删除吗' | 删除确认文本 |
| `dialog-width` | string | '' | 弹窗宽度 |
| `dialog-height` | string | '' | 弹窗高度 |
| `searchable` | boolean | true | 是否显示搜索 |

---

## Column 配置详解

```typescript
interface Column {
  // 基础配置
  type?: 'selection' | 'expand' | 'index' | 'operate'
  label?: string           // 列标题
  prop?: string            // 字段名 (支持 'category.name' 嵌套)
  width?: number | string  // 列宽
  'min-width'?: number     // 最小宽度
  align?: string           // 对齐: left/center/right
  fixed?: 'left' | 'right' // 固定列
  
  // 显示控制
  show?: boolean           // 是否显示 (默认 true)
  sortable?: boolean       // 是否可排序
  
  // 特殊列类型
  slot?: string            // 自定义插槽名
  header?: string          // 自定义表头插槽
  ellipsis?: boolean | number  // 文本溢出 (true=20字符)
  switch?: boolean         // 状态开关
  switchRefresh?: Function // 开关后回调
  image?: boolean          // 图片显示
  preview?: boolean        // 图片预览 (默认 false)
  link?: boolean           // 链接
  link_text?: string       // 链接文本
  route?: string           // Vue 路由 (支持 :id 参数)
  tags?: boolean | number[]  // 标签显示
  mask?: boolean           // 脱敏显示 (****)
  filter?: Function        // 数据过滤函数
  class?: string           // 自定义 CSS 类
  
  // 操作列配置 (type='operate')
  update?: boolean         // 显示编辑按钮 (默认 true)
  destroy?: boolean        // 显示删除按钮 (默认 true)
  
  // 多级表头
  children?: Column[]      // 子列配置
}
```

---

## 搜索表单配置

```typescript
interface SearchField {
  type: 'input' | 'select' | 'input-number' | 'date' | 'datetime' | 'range'
  label: string           // 标签
  name: string            // 字段名
  api?: string            // 下拉选项 API
  options?: Array<{       // 静态选项
    label: string
    value: string | number
  }>
  placeholder?: string
  default?: any           // 默认值
  props?: object          // 树形选择器 props
  show?: boolean          // 是否显示
}
```

---

## catch-table Slots

| 插槽 | 说明 |
|------|------|
| `#dialog="row"` | 表单弹窗内容 (必填) |
| `#{prop}="scope"` | 自定义列内容 |
| `#operate="scope"` | 额外操作按钮 |
| `#_operate="scope"` | 完全自定义操作列 |
| `#operation` | 左侧工具栏按钮 |
| `#multiOperate` | 批量操作按钮 |
| `#csearch` | 搜索表单额外内容 |
| `#middle` | 搜索和表格之间内容 |

---

## catch-table Methods (ref 调用)

```typescript
const catchtable = ref()

// 执行搜索
catchtable.value.doSearch(params, merge)

// 打开弹窗
catchtable.value.openDialog(row, title)

// 关闭弹窗
catchtable.value.closeDialog(isReset)

// 重置搜索
catchtable.value.reset()

// 删除
catchtable.value.del(api, id)

// 设置默认参数
catchtable.value.setDefaultParams(params)

// 获取多选 IDs
catchtable.value.getMultiSelectIds()

// 获取当前查询参数
catchtable.value.getTableQuery()
```

---

## Provide/Inject

```typescript
// catch-table 提供
provide('closeDialog', (isReset) => {})
provide('refresh', () => {})

// 在 create.vue 中使用
const closeDialog = inject('closeDialog')
const refresh = inject('refresh')
```

---

## 完整示例 index.vue

```vue
<template>
  <div>
    <catch-table
      ref="catchtable"
      :columns="columns"
      :api="api"
      :exports="true"
      :trash="true"
      permission="product.product"
      exportUrl="/product/export"
      importUrl="/product/import"
      :search-form="searchForm"
      :default-params="{ type: 1 }"
      row-key="id"
    >
      <!-- 自定义列 -->
      <template #image="scope">
        <el-image :src="scope.row.image" style="width: 50px" />
      </template>
      
      <template #price="scope">
        <span class="text-red-500">¥{{ scope.row.price }}</span>
      </template>
      
      <!-- 额外操作按钮 -->
      <template #operate="scope">
        <el-button type="primary" link @click="handleDetail(scope.row)">
          详情
        </el-button>
      </template>
      
      <!-- 左侧工具栏 -->
      <template #operation>
        <el-button @click="handleBatchExport">批量导出</el-button>
      </template>
      
      <!-- 批量操作 -->
      <template #multiOperate>
        <el-button type="warning" @click="handleBatchUpdate">
          批量更新
        </el-button>
      </template>
      
      <!-- 表单弹窗 -->
      <template #dialog="row">
        <Create :primary="row?.id" :api="api" />
      </template>
    </catch-table>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import Create from './create.vue'

const api = 'products'
const catchtable = ref()

const columns = [
  { type: 'selection' },
  { label: 'ID', prop: 'id', width: 80, sortable: true },
  { label: '图片', prop: 'image', slot: 'image', width: 80 },
  { label: '名称', prop: 'name', ellipsis: 30 },
  { label: '分类', prop: 'category.name', width: 120 },
  { label: '价格', prop: 'price', slot: 'price', width: 100 },
  { label: '库存', prop: 'stock', width: 80 },
  { 
    label: '状态', 
    prop: 'status', 
    switch: true, 
    width: 80,
    tags: ['success', 'danger']  // 1=success, 2=danger
  },
  { label: '创建时间', prop: 'created_at', width: 180, sortable: true },
  { 
    type: 'operate', 
    label: '操作', 
    width: 200, 
    fixed: 'right',
    update: true, 
    destroy: true 
  }
]

const searchForm = [
  { type: 'input', label: '名称', name: 'name' },
  { type: 'select', label: '分类', name: 'category_id', api: 'categories' },
  { type: 'select', label: '状态', name: 'status', options: [
    { label: '启用', value: 1 },
    { label: '禁用', value: 2 }
  ]},
  { type: 'range', label: '创建时间', name: 'created_at' }
]

// 自定义搜索
const handleSearch = (params) => {
  catchtable.value.doSearch(params)
}

// 详情
const handleDetail = (row) => {
  // router.push(`/product/detail/${row.id}`)
}

// 批量导出
const handleBatchExport = () => {
  const ids = catchtable.value.getMultiSelectIds()
  // ...
}
</script>
```

---

## 完整示例 create.vue

```vue
<template>
  <el-form :model="formData" label-width="100px" ref="form" v-loading="loading">
    <el-form-item
      label="名称"
      prop="name"
      :rules="[{ required: true, message: '请输入名称' }]"
    >
      <el-input v-model="formData.name" placeholder="请输入名称" maxlength="100" />
    </el-form-item>

    <el-form-item
      label="分类"
      prop="category_id"
      :rules="[{ required: true, message: '请选择分类' }]"
    >
      <el-select v-model="formData.category_id" placeholder="请选择" class="w-full">
        <el-option
          v-for="item in categories"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
    </el-form-item>

    <el-form-item
      label="价格"
      prop="price"
      :rules="[{ required: true, message: '请输入价格' }]"
    >
      <el-input-number v-model="formData.price" :precision="2" :min="0" class="w-full" />
    </el-form-item>

    <el-form-item label="库存" prop="stock">
      <el-input-number v-model="formData.stock" :min="0" class="w-full" />
    </el-form-item>

    <el-form-item label="图片" prop="image">
      <upload v-model="formData.image" />
    </el-form-item>

    <el-form-item label="描述" prop="description">
      <el-input v-model="formData.description" type="textarea" :rows="4" />
    </el-form-item>

    <el-form-item label="状态" prop="status">
      <el-radio-group v-model="formData.status">
        <el-radio :value="1">启用</el-radio>
        <el-radio :value="2">禁用</el-radio>
      </el-radio-group>
    </el-form-item>

    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">提交</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { inject, onMounted, ref } from 'vue'
import { useCreate } from '@/composables/curd/useCreate'
import { useShow } from '@/composables/curd/useShow'
import http from '@/support/http'

const props = defineProps<{
  primary?: string | number
  api: string
}>()

// CRUD composables
const { formData, form, loading, submitForm, close } = useCreate(props.api, props.primary)

// 编辑模式加载数据
if (props.primary) {
  useShow(props.api, props.primary, formData)
}

// 关闭弹窗
const closeDialog = inject('closeDialog')
onMounted(() => {
  close(() => closeDialog?.())
})

// 加载分类选项
const categories = ref([])
onMounted(async () => {
  const res = await http.get('categories')
  categories.value = res.data.data
})
</script>
```

---

## 特殊列用法

### 嵌套字段
```javascript
{ label: '分类', prop: 'category.name' }  // 访问关联对象
```

### 图片列
```javascript
{ label: '图片', prop: 'image', image: true }           // 不可预览
{ label: '图片', prop: 'image', image: true, preview: true }  // 可预览
```

### 链接列
```javascript
{ label: '链接', prop: 'url', link: true }
{ label: '链接', prop: 'url', link: true, link_text: '查看' }
```

### 路由链接
```javascript
{ label: '详情', prop: 'name', route: '/product/detail/:id' }
```

### 标签列
```javascript
{ label: '状态', prop: 'status', tags: true }  // 默认样式
{ label: '状态', prop: 'status', tags: ['success', 'danger', 'warning'] }
```

### 数据过滤
```javascript
{ 
  label: '价格', 
  prop: 'price', 
  filter: (val) => `¥${val.toFixed(2)}`
}
```

### 脱敏显示
```javascript
{ label: '手机', prop: 'mobile', mask: true }  // 138****8888
```

---

## 树形表格

```vue
<catch-table
  :columns="columns"
  api="categories"
  row-key="id"
  :pagination="false"
>
```

后端需返回嵌套结构或使用 `asTree()` 方法。
