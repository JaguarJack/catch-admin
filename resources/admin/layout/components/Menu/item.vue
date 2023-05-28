<template>
  <el-sub-menu :index="menu?.path" :class="subMenuClass" v-if="menu?.children?.length">
    <template #title>
      <el-icon>
        <Icon :name="menu?.meta?.icon" v-if="menu?.meta?.icon" class="text-sm" />
      </el-icon>
      <span>{{ menu?.meta?.title }}</span>
    </template>
    <slot />
  </el-sub-menu>

  <el-menu-item v-else class="ct-menu-item" :index="menu?.path" @click="isMiniScreen() && store.changeExpaned()">
    <el-icon>
      <Icon :name="menu?.meta?.icon" v-if="menu?.meta?.icon" class="text-sm" />
    </el-icon>
    <span v-if="menu?.path.indexOf('https://') !== -1 || menu?.path.indexOf('http://') !== -1">
      <span @click="openUrl(menu?.path as string)">{{ menu?.meta?.title }}</span>
    </span>
    <span v-else>{{ menu?.meta?.title }}</span>
  </el-menu-item>
</template>

<script lang="ts" setup>
import { Menu } from '/admin/types/Menu'
import { PropType } from 'vue'
import { useAppStore } from '/admin/stores/modules/app'
import { isMiniScreen } from '/admin/support/helper'

const store = useAppStore()

defineProps({
  subMenuClass: {
    type: String,
    require: true,
    default: '',
  },

  menu: {
    type: Object as PropType<Menu>,
    require: true,
  },
})

const openUrl = (path: string) => {
  const start = path.indexOf('https://') || path.indexOf('http://')
  window.open(path.substring(start))
  return false
}
</script>

<style scoped lang="scss">
:deep(.el-menu) {
  background-color: var(--sider-sub-menu-bg-color);
}

.ct-menu-item:hover {
  background-color: var(--sider-sub-menu-hover-bg-color) !important;
}

:deep(.children-menu .el-sub-menu__title) {
  background-color: var(--sider-sub-menu-bg-color) !important;
}

:deep(.el-menu-item-group__title) {
  padding: 0;
}
</style>
