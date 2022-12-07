<template>
  <el-form :model="formData" label-width="120px" ref="form" v-loading="loading" class="pr-4">
    <el-form-item label="岗位名称" prop="job_name" :rules="[{ required: true, message: '岗位名称必须填写' }]">
      <el-input v-model="formData.job_name" name="job_name" clearable />
    </el-form-item>
    <el-form-item label="岗位编码" prop="coding">
      <el-input v-model="formData.coding" name="coding" clearable />
    </el-form-item>
    <el-form-item label="状态" prop="status">
      <el-radio-group v-model="formData.status">
        <el-radio v-for="item in options" :key="item.value" :label="item.value" name="status">{{ item.label }}</el-radio>
      </el-radio-group>
    </el-form-item>
    <el-form-item label="排序" prop="sort">
      <el-input-number v-model="formData.sort" name="sort" :min="1" />
    </el-form-item>
    <el-form-item label="岗位描述" prop="description">
      <el-input v-model="formData.description" name="description" clearable type="textarea" />
    </el-form-item>
    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">{{ $t('system.confirm') }}</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { useCreate } from '/admin/composables/curd/useCreate'
import { useShow } from '/admin/composables/curd/useShow'

import { onMounted } from 'vue'

const props = defineProps({
  primary: String | Number,
  api: String,
})

const { formData, form, loading, submitForm, close } = useCreate(props.api, props.primary)

formData.value.status = 1
formData.value.sort = 1

if (props.primary) {
  useShow(props.api, props.primary, formData)
}
const emit = defineEmits(['close'])

onMounted(() => {
  close(() => emit('close'))
})

const options = [
  { label: '正常', value: 1 },
  { label: '禁用', value: 2 },
]
</script>
