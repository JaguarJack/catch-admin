<template>
  <el-button type="success" :size="size"><Icon name="pencil-square" className="w-4 h-4 mr-1" /> {{ text }}</el-button>
</template>

<script lang="ts" setup>
defineProps({
  size: {
    type: String,
    default: 'small',
  },
  text: {
    type: String,
    default: '更新',
  },
})
</script>

<style scoped></style>
