<template>
  <el-upload ref="upload" :action="action" :auto-upload="auto" v-bind="$attrs" :data="data" :before-upload="initOss" :on-success="handleSuccess">
    <template v-for="(index, name) in $slots" v-slot:[name]>
      <slot :name="name"></slot>
    </template>
  </el-upload>
</template>

<script setup lang="ts">
import http from '/admin/support/http'
import { ref } from 'vue'
import Message from '/admin/support/message'
const props = defineProps({
  auto: {
    type: Boolean,
    default: true,
  },
  modelValue: {
    type: Boolean,
    default: false,
    require: true,
  },
})

const action = ref('')
const data = ref({
  OSSAccessKeyId: '',
  policy: '',
  Signature: '',
  key: '',
  host: '',
  dir: '',
  expire: '',
  success_action_status: 200,
})
const emits = defineEmits(['update:modelValue'])
const initOss = async (file: { size: number; name: any }) => {
  if (file.size > 10 * 1024 * 1024) {
    Message.error('最大支持 10MB 文件')
    return
  }

  await http.get('upload/oss').then(r => {
    const { accessKeyId, bucket, dir, expire, host, policy, signature, url } = r.data.data
    action.value = host
    data.value.OSSAccessKeyId = accessKeyId
    data.value.policy = policy
    data.value.Signature = signature
    data.value.key = dir + file.name
    data.value.host = host
    data.value.dir = dir
    data.value.expire = expire
  })
}

const handleSuccess = (r: any) => {
  emits('update:modelValue', action.value + data.value.key)
}
</script>
