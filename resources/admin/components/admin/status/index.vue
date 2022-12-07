<template>
  <el-switch @change="enabled(api, id)" :active-value="Status.ENABLE" :inactive-value="Status.DISABLE" :model-value="modelValue" :loading="loading" />
</template>

<script lang="ts" setup>
import { useEnabled } from '/admin/composables/curd/useEnabled'
import { Status } from '/admin/enum/app'

const props = defineProps({
  modelValue: Boolean | Number | String,
  api: String,
  id: Number | String,
})

const emits = defineEmits(['update:modelValue', 'refresh'])

const { enabled, success, loading, afterEnabled } = useEnabled()

success(() => {
  emits('update:modelValue', props.modelValue === Status.ENABLE ? Status.DISABLE : Status.ENABLE)
})

afterEnabled.value = () => {
  emits('refresh')
}
</script>
