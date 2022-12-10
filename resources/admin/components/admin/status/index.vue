<template>
  <el-switch @change="enabled(api, id)" :active-value="activeValue" :inactive-value="inactiveValue" :model-value="modelValue" :loading="loading" />
</template>

<script lang="ts" setup>
import { useEnabled } from '/admin/composables/curd/useEnabled'
import { Status } from '/admin/enum/app'
import { ref } from 'vue'

const props = defineProps({
  modelValue: Boolean | Number | String,
  api: String,
  id: Number | String,
})

const emits = defineEmits(['update:modelValue', 'refresh'])

const { enabled, success, loading, afterEnabled } = useEnabled()

const activeValue = ref<boolean | number | string>()
const inactiveValue = ref<boolean | number | string>()

if (typeof props.modelValue === 'boolean') {
  activeValue.value = true
  inactiveValue.value = false
} else {
  activeValue.value = Status.ENABLE
  inactiveValue.value = Status.DISABLE
}

success(() => {
  emits('update:modelValue', props.modelValue === activeValue.value ? inactiveValue.value : activeValue.value)
})

afterEnabled.value = () => {
  emits('refresh')
}
</script>
