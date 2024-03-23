<template>
  <el-menu
    :default-active="appStore.getActiveMenu"
    background-color="var(--sider-menu-bg-color)"
    active-text-color="var(--sider-ment-active-text-color)"
    text-color="var(--sider-menu-text-color)"
    :collapse="!appStore.isExpand"
    :collapse-transition="false"
    :router="true"
    :unique-opened="true"
  >
    <slot />
  </el-menu>
</template>
<script lang="ts" setup>
import { useAppStore } from '/admin/stores/modules/app'
import { watch } from 'vue'
import router from '/admin/router'
import { useNavTabStore } from '/admin/stores/modules/tabs'

const appStore = useAppStore()
const navTabStore = useNavTabStore()
watch(() => router.currentRoute, (to, from) => {
    const tab: any = {
        name: to.value.name,
        fullPath: to.value.fullPath,
        path: to.value.path,
        is_active: true,
        meta: {
            title: to.value.meta.title,
            affix: false,
        }
    }
    navTabStore.addTabs(tab)
}, {deep:true, immediate:true})
</script>

<style scoped lang="scss">
.el-menu {
  border-right: none;
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
  border-right: 3px solid;
  border-right-color: var(--el-color-primary);
}

:deep(.el-menu-item.is-active) {
  border-right: 3px solid;
  border-right-color: var(--el-color-primary);
}

:deep(.el-menu-item) {
  height: 50px !important;
}
</style>
