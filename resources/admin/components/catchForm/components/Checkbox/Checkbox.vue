<template>
  <div v-if="!options.length" style="font-size: 12px">暂无选项</div>

  <el-checkbox-group v-bind="$attrs" v-model="modelValue" @change="selectChange">
    <template v-if="optionType === 'circle' || optionType === 'border'">
      <el-checkbox v-for="item in options" :key="item[valueKey]" :value="item[valueKey]" :border="optionType === 'border'" :disabled="item.disabled">{{ item[label] }}</el-checkbox>
    </template>

    <el-space v-if="optionType === 'button'" wrap :size="[space, space]">
      <el-checkbox-button v-for="item in options" :key="item[valueKey]" :value="item[valueKey]" size="large" :disabled="item.disabled">{{ item[label] }}</el-checkbox-button>
    </el-space>
  </el-checkbox-group>
</template>

<script setup>
import { onMounted } from 'vue'

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
  autoSelectedFirst: {
    type: Boolean,
    default: false
  },
  api: Object,
  name: String,
  optionType: {
    type: String,
    default: 'circle'
  },
  space: {
    type: Number,
    default: 0
  },
  multiple: {
    type: Boolean,
    default: true // 不可更改
  },
  value: {
    type: Array,
    default: []
  },
})

// const emits = defineEmits(['update:modelValue', 'onChangeSelect'])
const modelValue = defineModel()
const selectChange = value => {
  modelValue.value = value
}

onMounted(() => {
    modelValue.value = props.value
})
// const { selectVal, currentOptions, selectChange, loading } = useSelect(props, emits)
</script>

<style lang="scss" scoped></style>
