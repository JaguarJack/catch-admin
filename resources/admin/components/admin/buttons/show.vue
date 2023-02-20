<template>
  <el-button type="primary" :size="size"><Icon name="eye" className="w-4 h-4 mr-1" /> {{ text }}</el-button>
</template>

<script lang="ts" setup>
defineProps({
  size: {
    type: String,
    default: 'small',
  },
  text: {
    type: String,
    default: '详情',
  },
})
</script>
