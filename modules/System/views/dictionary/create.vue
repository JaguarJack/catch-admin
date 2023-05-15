<template>
  <el-form :model="formData" label-width="120px" ref="form" v-loading="loading" class="pr-4">
    <el-form-item
      label="字典名称"
      prop="name"
      :rules="[
        {
          required: true,
          message: '字典名称必须填写',
        },
      ]"
    >
      <el-input v-model="formData.name" name="name" clearable />
    </el-form-item>
    <el-form-item
      label="字典键名"
      prop="key"
      :rules="[
        {
          required: true,
          message: '字典键名必须填写',
        },
      ]"
    >
      <el-input v-model="formData.key" name="key" clearable />
    </el-form-item>
    <el-form-item label="字典描述" prop="description">
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

if (props.primary) {
  useShow(props.api, props.primary, formData)
}

const emit = defineEmits(['close'])
onMounted(() => {
  close(() => emit('close'))
})
</script>
