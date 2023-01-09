<template>
  <el-upload ref="upload" :action="actionApi" :auto-upload="auto" :headers="{ authorization: token }" v-bind="$attrs">
    <template v-for="(index, name) in $slots" v-slot:[name]>
      <slot :name="name"></slot>
    </template>
  </el-upload>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { env } from '/admin/support/helper'
import { getAuthToken } from '/admin/support/helper'

const props = defineProps({
  action: {
    type: String,
    default: 'upload',
  },
  auto: {
    type: Boolean,
    default: true,
  },
})

const baseURL = env('VITE_BASE_URL')

const actionApi = ref<string>('')

actionApi.value = baseURL + props.action

const token = ref<string>()
token.value = 'Bearer ' + getAuthToken()
</script>
