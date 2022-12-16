<template>
  <el-form :model="formData" label-width="120px" ref="form" v-loading="loading" class="pr-6">
    <el-form-item label="上级角色" prop="parent_id">
      <el-cascader
        :options="roles"
        name="parent_id"
        v-model="formData.parent_id"
        clearable
        check-strictly
        class="w-full"
        @change="getPermissions"
        :props="{ value: 'id', label: 'role_name', checkStrictly: true }"
      />
    </el-form-item>
    <el-form-item
      label="角色名称"
      prop="role_name"
      :rules="[
        {
          required: true,
          message: '角色名称必须填写',
        },
      ]"
    >
      <el-input v-model="formData.role_name" name="role_name" clearable />
    </el-form-item>
    <el-form-item
      label="角色标识"
      prop="identify"
      :rules="[
        {
          required: true,
          message: '角色标识必须填写',
        },
      ]"
    >
      <el-input v-model="formData.identify" name="identify" clearable />
    </el-form-item>

    <el-form-item label="角色描述" prop="description">
      <el-input v-model="formData.description" name="description" clearable type="textarea" />
    </el-form-item>
    <el-form-item label="数据权限" prop="data_range">
      <Select v-model="formData.data_range" name="data_range" clearable api="dataRange" class="w-full" />
    </el-form-item>
    <el-form-item label="选择权限" prop="permissions">
      <el-tree
        ref="permissionTree"
        v-model="formData.permissions"
        :data="permissions"
        node-key="id"
        class="w-full"
        :props="{ label: 'permission_name', value: 'id' }"
        show-checkbox
        default-expand-all
        @check="selectPermissions"
        :empty-text="permissionLoadingText"
      />
    </el-form-item>
    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">{{ $t('system.confirm') }}</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { useCreate } from '/admin/composables/curd/useCreate'
import { useShow } from '/admin/composables/curd/useShow'
import { nextTick, onMounted, ref, unref } from 'vue'
import http from '/admin/support/http'

const props = defineProps({
  primary: String | Number,
  api: String,
  hasPermissions: Array<Object>,
})

const { formData, form, loading, submitForm, close, beforeCreate, beforeUpdate } = useCreate(props.api, props.primary)

if (props.primary) {
  const { afterShow } = useShow(props.api, props.primary, formData)

  afterShow.value = formData => {
    const data = unref(formData)
    data.parent_id = data.parent_id ? [data.parent_id] : 0

    if (!data.data_range) {
      data.data_range = null
    }

    formData.value = data
  }
}

const emit = defineEmits(['close'])
const roles = ref()
const permissions = ref()
// 权限树对象
const permissionTree = ref()
const permissionLoadingText = ref<string>('加载中...')
const getPermissions = async (value: number = 0) => {
  if (value) {
    http.get('permissions/roles/' + getParent(value)).then(r => {
      permissions.value = r.data.data.permissions
      setCheckedPermissions()
    })
  } else {
    http.get('permissions/permissions').then(r => {
      permissions.value = r.data.data
      setCheckedPermissions()
    })
  }
}

const setCheckedPermissions = () => {
  nextTick(() => {
    props.hasPermissions.forEach(p => {
      permissionTree.value.setChecked(p.id, true, false)
    })
  })

  if (!permissions.value.length) {
    permissionLoadingText.value = '暂无数据'
  }
}
const getRoles = () => {
  http.get(props.api, { id: props.primary ? props.primary : '' }).then(r => {
    roles.value = r.data.data
  })
}

onMounted(() => {
  getRoles()
  getPermissions()
  close(() => emit('close'))
})

const selectPermissions = (checkedNodes, checkedKeys) => {
  formData.value.permissions = checkedKeys.checkedKeys.concat(checkedKeys.halfCheckedKeys).sort()
}

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
<style scoped>
:deep(.el-tree .el-tree__empty-block .el-tree__empty-text) {
  @apply left-10 top-4;
}
</style>
