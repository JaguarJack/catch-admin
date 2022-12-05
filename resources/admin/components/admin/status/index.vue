<template>
  <el-switch @change="enabled(api, id)" :active-value="Status.ENABLE" :inactive-value="Status.DISABLE" :model-value="modelValue" :loading="loading" />
</template>

<script lang="ts" setup>
import { useEnabled } from '/admin/composables/curd/useEnabled'
import { Status } from '/admin/enum/app'
import { watch } from 'vue'

const props = defineProps({
  modelValue: Boolean | Number | String,
  api: String,
  id: Number | String,
})

const emits = defineEmits(['update:modelValue'])

const { enabled, success, loading } = useEnabled()

watch(success, function () {
  emits('update:modelValue', props.modelValue === Status.ENABLE ? Status.DISABLE : Status.ENABLE)
  success.value = false
})
</script>
