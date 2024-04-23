<template>
  <el-upload v-model:file-list="fileList" :action="action" :name="uploadKey" multiple :before-upload="beforeAvatarUpload" :limit="limit" :on-exceed="handleExceed" v-bind="$attrs">
    <el-button type="primary">
      <template #icon><IconRender name="upload" /></template>
      {{ buttonText }}
    </el-button>
  </el-upload>
</template>

<script setup>
import { onBeforeMount, computed } from 'vue'
import { ElUpload, ElButton, ElMessage } from 'element-plus'

const props = defineProps({
  action: String,
  height: Number,
  width: Number,
  fileTypes: { type: Array, default: () => [] },
  size: Number,
  dataPath: {
    type: String,
    default: 'data'
  },
  uploadKey: {
    type: String,
    default: 'file'
  },
  buttonText: {
    type: String,
    default: '点击上传'
  },
  limit: {
    type: Number,
    default: 2
  }
})

const modelValue = defineModel({
  type: Array,
  required: true
})

const fileList = computed({
  get() {
    return modelValue.value
  },
  set(val) {
    modelValue.value = val
  }
})

const beforeAvatarUpload = rawFile => {
  const [, type] = rawFile.type.split('/')

  // if (!props.fileTypes.includes(type)) {
  //   ElMessage.error('不支持该图片格式!')
  //   return false
  // }

  if (rawFile.size / 1024 / 1024 > props.size) {
    ElMessage.error(`图片文件大小不能超过${props.size}MB！`)
    return false
  }

  return true
}

const handleExceed = () => {
  ElMessage.warning(`上传数量限制为 ${props.limit} 个！`)
}

onBeforeMount(() => {
  const { modelValue } = props

  if (modelValue) {
    imageUrl.value = modelValue
  }
})
</script>
