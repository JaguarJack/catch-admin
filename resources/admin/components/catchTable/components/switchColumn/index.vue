<template>
  <el-switch @change="enabeldField" :active-value="activeValue" :inactive-value="inactiveValue" :model-value="modelValue" :loading="loading" />
</template>

<script lang="ts" setup>
import { useEnabled } from '/admin/composables/curd/useEnabled'
import { Status } from '/admin/enum/app'
import { ref, inject } from 'vue'

const props = defineProps({
  api: {
    required: true,
    type: String
  },
  id: {
    required: false,
    type: [String, Number]
  },
  field: {
    require: false,
    type: String,
    default: 'status'
  },
  refresh: {
    type: Function,
    defaulat: null,
    required: false
  }
})

const modelValue = defineModel()
// @ts-ignore
const { enabled, success, loading, afterEnabled } = useEnabled()

const activeValue = ref<boolean | number | string>()
const inactiveValue = ref<boolean | number | string>()

if (typeof modelValue.value === 'boolean') {
  activeValue.value = true
  inactiveValue.value = false
} else {
  activeValue.value = Status.ENABLE
  inactiveValue.value = Status.DISABLE
}

success(() => {
  modelValue.value = modelValue.value === activeValue.value ? inactiveValue.value : activeValue.value
})

afterEnabled.value = () => {
  if (props.refresh) {
    props.refresh()
  } else {
    const refresh = inject('refresh') as Function
    refresh()
  }
}

const enabeldField = () => {
  enabled(props.api, props.id as string | number, { field: props.field })
}
</script>
