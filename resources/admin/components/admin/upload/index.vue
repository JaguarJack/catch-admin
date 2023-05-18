<template>
  <el-upload
    ref="upload"
    :action="actionApi"
    :show-file-list="false"
    name="image"
    :auto-upload="auto"
    :headers="{ authorization: token, 'Request-from': 'Dashboard' }"
    v-bind="$attrs"
    :on-success="handleSuccess"
  >
    <template v-for="(index, name) in $slots" v-slot:[name]>
      <slot :name="name"></slot>
    </template>
    <img :src="modelValue" v-if="modelValue" :class="imageClass" />
    <div v-else class="w-24 h-24 border-blue-100 border-dashed border rounded flex justify-center pt-8">
      <Icon name="plus" />
    </div>
  </el-upload>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { env } from '/admin/support/helper'
import { getAuthToken } from '/admin/support/helper'
import { Code } from '/admin/enum/app'
import Message from '/admin/support/message'

const props = defineProps({
  action: {
    type: String,
    default: 'upload/image',
  },
  auto: {
    type: Boolean,
    default: true,
  },
  modelValue: {
    type: String,
    default: '',
    require: true,
  },
  imageClass: {
    type: String,
    default: '',
  },
})

const emits = defineEmits(['update:modelValue'])
const baseURL = env('VITE_BASE_URL')

const actionApi = ref<string>('')

actionApi.value = baseURL + props.action

const token = ref<string>()
token.value = 'Bearer ' + getAuthToken()

const handleSuccess = (response: any) => {
  if (response.code === Code.SUCCESS) {
    emits('update:modelValue', response.data.path)
  } else {
    Message.error(response.message)
  }
}
</script>
