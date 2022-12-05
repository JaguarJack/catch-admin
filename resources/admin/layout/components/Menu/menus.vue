<template>
  <el-menu
      :default-active="appStore.getActiveMenu"
      background-color="var(--sider-menu-bg-color)"
      active-text-color="var(--sider-ment-active-text-color)"
      text-color="var(--sider-menu-text-color)"
      :collapse="!appStore.isExpand"
      :collapse-transition="false"
      :router="true"
      @select="selectMenu"
      :unique-opened="true"
  >
    <slot/>
  </el-menu>
</template>
<script lang="ts" setup name="menus">
import { useAppStore } from '/admin/stores/modules/app'

const appStore = useAppStore()

const selectMenu = (index: string) => {
  if (index.startsWith('http') || index.startsWith('https')) {
    window.open(index)
  }
}
</script>

<style scoped lang="scss">
.el-menu {
  border-right: none;
}

:deep(.el-menu--inline) {
  @apply pt-1 pb-2;
}

:deep(.ct-menu-item) {
  @apply mt-1;
}

:deep(.is-active) {
  background-color: var(--side-active-menu-bg-color) !important;
}

:deep(.el-sub-menu__title) {
  padding-left: calc(calc(var(--el-menu-base-level-padding) + var(--el-menu-level) * var(--el-menu-level-padding)));

  color: var(--sider-menu-text-color);
}

:deep(.el-sub-menu) {
  color: var(--sider-sub-menu-bg-color);
}

:deep(.el-sub-menu__title:hover) {
  background-color: var(--sider-menu-bg-color);
}

:deep(.el-menu--popup .el-menu-item:hover) {
  background-color: var(--sider-menu-bg-color) !important;
}

:deep(.el-menu-item:hover) {
  background-color: var(--sider-sub-menu-hover-bg-color) !important;
}
</style>