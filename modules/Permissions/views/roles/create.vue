<template>
  <el-form :model="formData" label-width="120px" ref="form" v-loading="loading" class="pr-6">
    <el-form-item label="上级角色" prop="parent_id">
      <el-cascader :options="roles" name="parent_id" v-model="formData.parent_id" clearable :props="{ value: 'id', label: 'role_name', checkStrictly: true }" class="w-full" />
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
    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">{{ $t('system.confirm') }}</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { useCreate } from '/admin/composables/curd/useCreate'
import { useShow } from '/admin/composables/curd/useShow'
import { onMounted, ref, watch } from 'vue'
import http from '/admin/support/http'

const props = defineProps({
  primary: String | Number,
  api: String,
})

const emit = defineEmits(['close'])

const { formData, form, loading, submitForm, isClose, beforeCreate, beforeUpdate } = useCreate(props.api, props.primary)

beforeCreate.value = () => {
  formData.value.parent_id = getParent(formData.value.parent_id)
}

beforeUpdate.value = () => {
  formData.value.parent_id = getParent(formData.value.parent_id)
}

const getParent = (parentId: any) => {
  return typeof parentId === 'undefined' ? 0 : parentId[parentId.length - 1]
}

watch(isClose, function (value) {
  if (value) {
    emit('close')
  }
})

const roles = ref()

onMounted(() => {
  if (props.primary) {
    useShow(props.api, props.primary).then(r => {
      r.data.parent_id = r.data.parent_id ? [r.data.parent_id ] : 0;

      formData.value = r.data

      if (!formData.value.data_range) {
        formData.value.data_range = null
      }
    })
  }

  http.get(props.api).then(r => {
    roles.value = r.data.data
  })
})
</script>
