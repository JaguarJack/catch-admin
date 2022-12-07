<template>
  <el-form :model="formData" label-width="120px" ref="form" v-loading="loading" class="pr-4">
    <el-form-item label="父级部门" prop="parent_id">
      <el-cascader :options="departments" name="parent_id" v-model="formData.parent_id" clearable :props="{ value: 'id', label: 'department_name', checkStrictly: true }" class="w-full" />
    </el-form-item>
    <el-form-item label="部门名称" prop="department_name" :rules="[{ required: true, message: '部门名称必须填写' }]">
      <el-input v-model="formData.department_name" name="department_name" clearable />
    </el-form-item>
    <el-form-item label="部门联系人" prop="principal">
      <el-input v-model="formData.principal" name="principal" clearable />
    </el-form-item>
    <el-form-item label="手机号" prop="mobile">
      <el-input v-model="formData.mobile" name="mobile" clearable />
    </el-form-item>
    <el-form-item label="邮箱" prop="email">
      <el-input v-model="formData.email" name="email" clearable />
    </el-form-item>
    <el-form-item label="排序" prop="sort">
      <el-input-number v-model="formData.sort" name="sort" :min="1" />
    </el-form-item>
    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">{{ $t('system.confirm') }}</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { useCreate } from '/admin/composables/curd/useCreate'
import { useShow } from '/admin/composables/curd/useShow'
import http from '/admin/support/http'
import { onMounted, ref, unref } from 'vue'

const props = defineProps({
  primary: String | Number,
  api: String,
})

const { formData, form, loading, submitForm, close, beforeCreate, beforeUpdate } = useCreate(props.api, props.primary)
formData.value.sort = 1

beforeCreate.value = () => {
  formData.value.parent_id = getParent(formData.value.parent_id)
}

beforeUpdate.value = () => {
  formData.value.parent_id = getParent(formData.value.parent_id)
}

const getParent = (parentId: any) => {
  return typeof parentId === 'undefined' ? 0 : parentId[parentId.length - 1]
}

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

const departments = ref()
onMounted(() => {
  http.get(props.api).then(r => {
    departments.value = r.data.data
  })

  close(() => {
    emit('close')
  })
})
</script>
