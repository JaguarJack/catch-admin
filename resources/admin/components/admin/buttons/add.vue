<template>
  <el-button type="primary" :size="size"><Icon name="plus" class="w-4 mr-1" /> {{ text }}</el-button>
</template>

<script lang="ts" setup>
defineProps({
  size: {
    type: String,
    default: 'default',
  },
  text: {
    type: String,
    default: '新增',
  },
})
</script>
