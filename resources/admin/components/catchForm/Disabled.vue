<template>
  <div v-if="disabled" v-bind="$attrs" class="disabled-wrapper">
    <div class="mask"></div>
    <slot />
  </div>

  <template v-else> <slot /> </template>
</template>

<script setup>
defineProps({
  disabled: Boolean
})
</script>

<style scoped lang="scss">
.disabled-wrapper {
  background-color: var(--el-disabled-bg-color);
  position: relative;
  .mask {
    cursor: not-allowed;
    position: absolute;
    height: 100%;
    width: 100%;
    left: 0;
    top: 0;
    z-index: 10;
  }
}
</style>
