<template>
  <span class="empty" v-if="!componentName">请输入全局注册得组件名</span>
  <component v-else :is="componentName" v-model="value" v-bind="$attrs" />
</template>

<script setup>
import { computed } from 'vue'

defineProps({
  componentName: String,
  modelValue: null
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

<style scoped>
.empty {
  font-size: 12px;
}
</style>
