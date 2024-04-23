<template>
  <el-popover placement="right" :width="400" trigger="click">
    <template #reference>
      <el-input v-model="iconModel" name="icon" clearable />
    </template>
    <div>
      <Icons v-model="iconModel" @close="closeSelectIcon" />
    </div>
  </el-popover>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const iconModel = defineModel()
// 选择 icon
const visible = ref(false)
// 关闭选择 icon
const closeSelectIcon = () => {
  visible.value = false
}
</script>
