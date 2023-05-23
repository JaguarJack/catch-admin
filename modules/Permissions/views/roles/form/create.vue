<template>
  <el-form :model="formData" label-width="120px" ref="form" v-loading="loading" class="pr-6">
    <el-form-item label="上级角色" prop="parent_id" v-if="!primary">
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
    <el-form-item
      label="自定义权限"
      prop="departments"
      v-if="showDepartments"
      :rules="[
        {
          required: true,
          message: '自定义权限必须选择',
        },
      ]"
    >
      <el-tree-select
        v-model="formData.departments"
        value-key="id"
        class="w-full"
        :data="departments"
        :render-after-expand="false"
        show-checkbox
        multiple
        :props="{ value: 'id', label: 'department_name' }"
      />
    </el-form-item>
    <el-form-item label="角色权限" prop="permissions">
      <div class="h-40 overflow-auto w-full border border-gray-300 rounded pt-2 pl-2">
        <el-tree
          ref="permissionTree"
          v-model="formData.permissions"
          :data="permissions"
          node-key="id"
          class="w-full"
          :props="{ label: 'permission_name', value: 'id' }"
          show-checkbox
          :default-expand-all="false"
          @check="selectPermissions"
          :empty-text="permissionLoadingText"
        />
      </div>
    </el-form-item>
    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">{{ $t('system.confirm') }}</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { useCreate } from '/admin/composables/curd/useCreate'
import { useShow } from '/admin/composables/curd/useShow'
import { nextTick, onMounted, ref, unref, watch } from 'vue'
import http from '/admin/support/http'

const props = defineProps({
  primary: String | Number,
  api: String,
  hasPermissions: Array<Object>,
})

const emit = defineEmits(['close'])

const { formData, form, loading, submitForm, close, beforeCreate, beforeUpdate } = useCreate(props.api, props.primary)

if (props.primary) {
  const { afterShow } = useShow(props.api, props.primary, formData)
  // 更新角色值
  afterShow.value = formData => {
    const data = unref(formData)
    data.parent_id = data.parent_id ? [data.parent_id] : 0

    if (!data.data_range) {
      data.data_range = null
    }

    formData.value = data
    // 这里需要获取角色的上级的权限以限制可用权限范围
    getPermissions(data.parent_id)
  }
}

const roles = ref()
const permissions = ref()
// 权限树对象
const permissionTree = ref()
//  部门
const departments = ref()
const showDepartments = ref<boolean>(false)

const permissionLoadingText = ref<string>('加载中...')

// 获取权限
const getPermissions = async (value: number = 0) => {
  if (value) {
    // 获取角色权限
    http.get('permissions/roles/' + getParent(value), { from: 'parent_role' }).then(r => {
      permissions.value = r.data.data.permissions
      setCheckedPermissions()
    })
  } else {
    http.get('permissions/permissions', { from: 'role' }).then(r => {
      permissions.value = r.data.data
      setCheckedPermissions()
    })
  }
}

// 设置已选权限
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

// 获取角色信息
const getRoles = () => {
  http.get(props.api, { id: props.primary ? props.primary : '' }).then(r => {
    roles.value = r.data.data
  })
}

const getDepartments = () => {
  http.get('permissions/departments').then(r => {
    departments.value = r.data.data
  })
}

// 新增默认获取全部权限
if (!props.primary) {
  getPermissions()
}

// 页面挂载完成后
onMounted(() => {
  getRoles()
  getDepartments()
  close(() => emit('close'))
  watch(
    formData,
    function (value) {
      // 如果数据权限是自定义数据
      showDepartments.value = value.data_range === 2
    },
    { deep: true },
  )
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
  const permissionIds = []
  formData.value.permissions.forEach(item => {
    permissionIds.push(item)
  })

  formData.value.permissions = permissionIds
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
:deep(.el-tree-node .is-expanded .el-tree-node__children) {
  @apply flex flex-wrap pl-9;
}
:deep(.el-tree-node .is-expanded .el-tree-node__children .el-tree-node__content) {
  padding-left: 0 !important;
}
</style>
