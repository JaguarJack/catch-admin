<template>
  <ElDivider v-bind="$attrs">{{ title }}</ElDivider>
</template>

<script setup>
import { ElDivider } from 'element-plus'

defineProps({
  title: String
})
</script>

<style></style>
