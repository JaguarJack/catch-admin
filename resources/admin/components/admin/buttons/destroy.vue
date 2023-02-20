<template>
  <el-button type="danger" :size="size"><Icon name="trash" className="w-4 h-4 mr-1" /> {{ text }}</el-button>
</template>

<script lang="ts" setup>
defineProps({
  size: {
    type: String,
    default: 'small',
  },
  text: {
    type: String,
    default: '删除',
  },
})
</script>
