<template>
  <el-cascader
    v-model="modelValue"
    :options="options"
    :show-all-levels="!takeLastLevel"
    :props="{
      multiple,
      value: valueKey,
      label,
      checkStrictly,
    }"
    v-bind="$attrs"
  />
</template>

<script setup lang="ts">
import { onMounted } from 'vue'

const props = defineProps({
  options: {
    type: Array,
    default: () => []
  },
  checkStrictly: {
    type: Boolean,
    default: false
  },
  multiple: {
    type: Boolean,
    default: false
  },
  label: {
    type: String,
    default: 'label'
  },
  valueKey: {
    type: String,
    default: 'value'
  },
  value: {
    type:[String, Number, Array],
    default: ''
  },
  api: Object,
  name: String,
  takeLastLevel: {
    type: Boolean,
    default: false
  }
})
const modelValue = defineModel()
onMounted(() => {
  modelValue.value = props.value
})
</script>
