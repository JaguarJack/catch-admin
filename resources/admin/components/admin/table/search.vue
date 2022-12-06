<template>
  <div class="w-full min-h-0 bg-white dark:bg-regal-dark pl-5 pt-5 pr-5 rounded-lg">
    <el-form :inline="true">
      <slot name="body" />
      <el-form-item>
        <el-button type="primary" @click="search()">
          <Icon name="magnifying-glass" class="w-4 mr-1 -ml-1" />
          搜索
        </el-button>
        <el-button @click="reset()">
          <Icon name="arrow-path" class="w-4 mr-1 -ml-1" />
          重置
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script lang="ts" setup>
defineProps({
  search: {
    type: Function,
    required: true,
  },

  reset: {
    type: Function,
    required: true,
  },
})
</script>

<style scoped></style>
