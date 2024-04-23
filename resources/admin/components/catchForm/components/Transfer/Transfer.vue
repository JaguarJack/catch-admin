<template>
  <Disabled :disabled="disabled"> <ElTransfer v-bind="$attrs" v-model="modelValue" :data="options" :props="{ label: props.label, key: props.valueKey }" /></Disabled>
</template>

<script setup>
import { ElTransfer } from 'element-plus'
import Disabled from '/admin/components/catchForm/Disabled.vue'

const modelValue = defineModel()
const props = defineProps({
  options: {
    type: Array,
    default: () => []
  },
  mode: {
    type: String,
    default: 'static'
  },
  label: {
    type: String,
    default: 'label'
  },
  valueKey: {
    type: String,
    default: 'value'
  },
  api: Object,
  disabled: Boolean
})

// const emits = defineEmits(['update:modelValue', 'onChangeSelect'])

// const { selectVal, currentOptions, selectChange, loading } = useSelect(props, emits)

// watchEffect(() => {
//   console.log(currentOptions.value)
// })
</script>
