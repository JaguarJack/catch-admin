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
        <el-form-item label="菜单名称" prop="permission_name">
          <el-input v-model="formData.permission_name" name="permission_name" clearable />
        </el-form-item>
        <el-form-item label="所属模块" prop="module">
          <Select v-model="formData.module" clearable api="modules" class="w-full" filterable />
        </el-form-item>
        <el-form-item label="菜单路由" prop="route">
          <el-input v-model="formData.route" name="route" clearable />
        </el-form-item>
        <el-form-item label="Redirect" prop="redirect">
          <el-input v-model="formData.redirect" name="redirect" clearable />
        </el-form-item>
        <el-form-item label="Keepalive" prop="keepalive">
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
      <div>
        <el-form-item label="父级菜单" prop="parent_id">
          <el-cascader :options="permissions" name="parent_id" v-model="formData.parent_id" clearable :props="{ value: 'id', label: 'permission_name', checkStrictly: true }" class="w-full" />
        </el-form-item>
        <el-form-item label="权限标识" prop="permission_mark">
          <el-input v-model="formData.permission_mark" name="permission_mark" clearable />
        </el-form-item>
        <el-form-item label="菜单Icon" prop="icon">
          <el-input v-model="formData.icon" name="icon" clearable />
        </el-form-item>
        <el-form-item label="所属组件" prop="component">
          <el-select v-model="formData.component" placeholder="请选择" clearable class="w-full" />
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input-number v-model="formData.sort" name="sort" :min="1" />
        </el-form-item>
        <el-form-item label="Hidden" prop="hidden">
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
      </div>
    </div>
    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">{{ $t('system.confirm') }}</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { useCreate } from '/admin/composables/curd/useCreate'
import { useShow } from '/admin/composables/curd/useShow'
import { onMounted, ref } from 'vue'
import http from '/admin/support/http'

const props = defineProps({
  primary: String | Number,
  api: String,
})

const { formData, form, loading, submitForm, close, beforeCreate, beforeUpdate } = useCreate(props.api, props.primary)

formData.value.sort = 1
formData.value.keepalive = 1
formData.value.type = 1
formData.value.hidden = 1

if (props.primary) {
  useShow(props.api, props.primary, formData)
}

const emit = defineEmits(['close'])
const permissions = ref()
onMounted(() => {
  http.get(props.api).then(r => {
    permissions.value = r.data.data
  })

  close(() => emit('close'))
})

beforeCreate.value = () => {
  formData.value.parent_id = getParent(formData.value.parent_id)
}

beforeUpdate.value = () => {
  formData.value.parent_id = getParent(formData.value.parent_id)
}

const getParent = (parentId: any) => {
  return typeof parentId === 'undefined' ? 0 : parentId[parentId.length - 1]
}
</script>
