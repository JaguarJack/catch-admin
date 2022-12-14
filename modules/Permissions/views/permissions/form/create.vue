<template>
  <el-form :model="formData" label-width="80px" ref="form" v-loading="loading" class="pr-4">
    <div class="flex flex-row justify-between">
      <div>
        <el-form-item label="菜单类型" prop="type">
          <el-radio-group v-model="formData.type">
            <el-radio-button
              v-for="item in [
                { label: '目录', value: 1 },
                { label: '菜单', value: 2 },
                { label: '按钮', value: 3 },
              ]"
              :key="item.value"
              :label="item.value"
              name="type"
              >{{ item.label }}
            </el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="菜单名称" prop="permission_name" :rules="[{ required: true, message: '菜单名称必须填写' }]">
          <Select v-model="formData.permission_name" name="permission_name" :options="actionMenuNames" v-if="isAction" />
          <el-input v-model="formData.permission_name" name="permission_name" clearable v-else />
        </el-form-item>
        <el-form-item label="所属模块" prop="module" :rules="[{ required: true, message: '所属模块必须填写' }]" v-if="!isAction">
          <Select v-model="formData.module" api="modules" @clear="clearModule" />
        </el-form-item>
        <el-form-item label="路由Path" prop="route" :rules="[{ required: true, message: '路由Path必须填写' }]" v-if="!isAction">
          <el-input v-model="formData.route" name="route" clearable />
        </el-form-item>
        <el-form-item label="Redirect" prop="redirect" v-if="!isAction">
          <el-input v-model="formData.redirect" name="redirect" clearable />
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input-number v-model="formData.sort" name="sort" :min="1" />
        </el-form-item>
      </div>
      <div>
        <el-form-item label="父级菜单" prop="parent_id">
          <el-cascader :options="permissions" name="parent_id" v-model="formData.parent_id" clearable :props="{ value: 'id', label: 'permission_name', checkStrictly: true }" class="w-full" />
        </el-form-item>
        <el-form-item label="权限标识" prop="permission_mark" :rules="[{ required: true, message: '权限标识必须填写' }]" v-if="!isTop">
          <Select v-model="formData.permission_mark" name="permission_mark" :options="actionMenuMark" allow-create v-if="isAction" />
          <Select v-model="formData.permission_mark" placeholder="请选择" api="controllers" :query="{ module: formData.module }" v-else />
        </el-form-item>
        <el-form-item label="菜单Icon" prop="icon" v-if="!isAction">
          <el-input v-model="formData.icon" name="icon" clearable @focus="open" />
        </el-form-item>
        <el-form-item label="所属组件" prop="component" :rules="[{ required: true, message: '所属组件必须填写' }]" v-if="!isAction">
          <Select v-model="formData.component" placeholder="请选择" allow-create api="components" :query="{ module: formData.module }" />
        </el-form-item>

        <el-form-item label="Hidden" prop="hidden" v-if="!isAction">
          <el-radio-group v-model="formData.hidden">
            <el-radio
              v-for="item in [
                { label: '显示', value: 1 },
                { label: '隐藏', value: 2 },
              ]"
              :key="item.value"
              :label="item.value"
              name="hidden"
              >{{ item.label }}</el-radio
            >
          </el-radio-group>
        </el-form-item>
        <el-form-item label="Keepalive" prop="keepalive" v-if="!isAction">
          <el-radio-group v-model="formData.keepalive">
            <el-radio
              v-for="item in [
                { label: '启用', value: 1 },
                { label: '禁用', value: 2 },
              ]"
              :key="item.value"
              :label="item.value"
              name="keepalive"
              >{{ item.label }}
            </el-radio>
          </el-radio-group>
        </el-form-item>
      </div>
    </div>
    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">{{ $t('system.confirm') }}</el-button>
    </div>
  </el-form>

  <Dialog v-model="visible" title="选择 Icon" width="1000px" destroy-on-close>
    <Icons v-model="formData.icon" @close="closeSelectIcon" />
  </Dialog>
</template>

<script lang="ts" setup>
import { useCreate } from '/admin/composables/curd/useCreate'
import { useShow } from '/admin/composables/curd/useShow'
import { useOpen } from '/admin/composables/curd/useOpen'

import { onMounted, ref, watch } from 'vue'
import http from '/admin/support/http'

const props = defineProps({
  primary: String | Number,
  api: String,
})

const { formData, form, loading, submitForm, close, beforeCreate, beforeUpdate } = useCreate(props.api, props.primary)
const { open, visible } = useOpen()

// 关闭选择
const closeSelectIcon = () => {
  visible.value = false
}
// 初始化
formData.value.sort = 1
formData.value.keepalive = 1
formData.value.type = 1
formData.value.hidden = 1
// 默认目录
const isTop = ref<boolean>(true)
const isMenu = ref<boolean>(false)
const isAction = ref<boolean>(false)

// 回显示表单
if (props.primary) {
  useShow(props.api, props.primary, formData)
}

const emit = defineEmits(['close'])
const permissions = ref()
onMounted(() => {
  http.get(props.api).then(r => {
    permissions.value = r.data.data
  })
  // close dialog
  close(() => emit('close'))

  // 监听 form data
  watch(
    formData,
    (value, oldValue) => {
      const type: number = formData.value.type

      if (type === 1) {
        isTop.value = true
        isMenu.value = isAction.value = false
      } else if (type === 2) {
        isMenu.value = true
        isTop.value = isAction.value = false
      } else {
        isAction.value = true
        isTop.value = isMenu.value = false
      }
    },
    { deep: true },
  )
})

// 菜单是菜单类型的时，清除模块，那么权限标识&组件也需要清除
const clearModule = () => {
  if (formData.value.type === 1 || formData.value.type === 2) {
    formData.value.component = null
  }
  if (formData.value.type === 2) {
    formData.value.permission_mark = null
  }
}

// 当菜单是按钮类型时, 定义两个初始值
const actionMenuNames = [
  { label: '列表', value: '列表' },
  { label: '新增', value: '新增' },
  { label: '读取', value: '读取' },
  { label: '更新', value: '更新' },
  { label: '删除', value: '删除' },
  { label: '禁用/启用', value: '禁用/启用' },
  { label: '导入', value: '导入' },
  { label: '导出', value: '导出' },
]

const actionMenuMark = [
  { label: 'index', value: 'index' },
  { label: 'store', value: 'store' },
  { label: 'show', value: 'show' },
  { label: 'update', value: 'update' },
  { label: 'destroy', value: 'destroy' },
  { label: 'enable', value: 'enable' },
  { label: 'import', value: 'import' },
  { label: 'export', value: 'export' },
]
// 创建前的钩子
beforeCreate.value = () => {
  formData.value.parent_id = getParent(formData.value.parent_id)
}

// 更新前的钩子
beforeUpdate.value = () => {
  formData.value.parent_id = getParent(formData.value.parent_id)
}

const getParent = (parentId: any) => {
  return typeof parentId === 'undefined' ? 0 : parentId[parentId.length - 1]
}
</script>
