<template>
  <el-upload
    :action="action"
    :show-file-list="false"
    name="image"
    :auto-upload="auto"
    :headers="{ authorization: token, 'Request-from': requestFrom }"
    ref="upload"
    :on-exceed="handleExceed"
    :on-success="handleSuccess"
    :before-upload="beforeUpload"
    :limit="1"
  >
    <img v-if="fileModel" :src="fileModel" :class="class" />
    <div v-else>
      <div class="flex items-center justify-center w-24 h-24 border border-collapse">
        <el-icon><Plus /></el-icon>
      </div>
    </div>
  </el-upload>
</template>
<script setup lang="ts">
import { watch } from 'vue'
import { uploadImage } from '/admin/composables/upload'
import { Plus } from '@element-plus/icons-vue'

const props = defineProps({
  action: {
    type: String
  },
  name: {
    type: String,
    default: 'image'
  },
  auto: {
    type: Boolean,
    default: true
  },
  // eslint-disable-next-line vue/no-reserved-props
  class: {
    type: String,
    default: 'w-24 h-24'
  },
  requestFrom: {
   type: String,
   default: 'Dashboard'
  },
  token:{
     type: String
  },
  ext: {
    type: Array,
    default: () => ['jpg', 'jpeg', 'png', 'bmp', 'gif']
  }
})

// 定义文件 v-model
const fileModel = defineModel({
  type: String,
  default: '',
  required: true
})
const { upload, beforeUpload, handleExceed, handleSuccess, file } = uploadImage(props.action, props.ext)
// 更新 model 的 value
watch(
  () => file.value,
  (newValue, oldValue) => {
    fileModel.value = newValue
  }
)
</script>
