<template>
  <span id="NumberInput">
    <el-input-number v-bind="{ ...$attrs, ...props }" v-model="value" />
    <span class="unit" v-if="unit">{{ unit }} </span>
  </span>
</template>

<script setup>
import { computed } from 'vue'
import { ElInputNumber } from 'element-plus'

const props = defineProps({
  unit: String,
  min: { type: Number, default: 0 },
  max: Number,
  disabled: Boolean,
  step: { type: Number, default: 1 }
})

const modelValue = defineModel()

const value = computed({
  get() {
    return modelValue.value
  },
  set(val) {
    modelValue.value = val
  }
})
</script>

<style lang="scss" scoped>
#NumberInput {
  position: relative;
  .unit {
    margin: 0 5px;
  }
}
</style>
