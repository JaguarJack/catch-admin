<template>
  <el-form :model="formData" label-width="120px" ref="form" v-loading="loading" class="pr-4">
    <el-form-item label="父级分类" prop="parent_id">
      <el-select v-model="formData.parent_id" placeholder="请选择" clearable multiple>
        <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value" />
      </el-select>
    </el-form-item>
    <el-form-item label="分类名称" prop="name">
      <el-input v-model="formData.name" name="name" clearable />
    </el-form-item>
    <el-form-item label="缩略名" prop="slug">
      <el-input v-model="formData.slug" name="slug" clearable />
    </el-form-item>
    <el-form-item label="排序" prop="order">
      <el-input-number v-model="formData.order" name="order" :min="1" />
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
