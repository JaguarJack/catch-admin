<template>
  <div>
    <div :class="sideClass + ' drop-shadow-md overflow-y'">
      <!--logo -->
      <Logo />
      <!-- menu item -->
      <Menu :menu-class="menuClass" />
    </div>
    <Mask v-if="isMobile && appStore.isExpand" @click="appStore.changeExpaned()" />
  </div>
</template>

<script lang="ts" setup>
import { useAppStore } from '/admin/stores/modules/app'
import { computed, onMounted, ref, watch } from 'vue'
import { isMiniScreen } from '/admin/support/helper'

const isMobile = ref(isMiniScreen())
const layoutSide = ' h-screen z-[1000] sm:z-0 absolute top-0 left-0 sm:fixed transition-width duration-300 ease-linear sider-bg overflow-auto'
const layoutSideOpenClass = 'w-56' + layoutSide
const layoutSideHiddenClass = 'w-0 sm:w-16' + layoutSide

// 是否是小屏幕
const sideClass = computed(() => {
  return appStore.isExpand ? layoutSideOpenClass : layoutSideHiddenClass
})

// menu class
const menuClass = ref<string>()
// 判断展开状态
const appStore = useAppStore()
watch(appStore.$state, state => {
  // 如果切换到小屏幕，并且是菜单是收缩状态
  menuClass.value = isExpandWhenInMobile()
})

// 监控屏幕大小
onMounted(() => {
  window.onresize = () => {
    return (() => {
      // 如果切换到小屏幕，并且是菜单是收缩状态，则隐藏子菜单
      isMobile.value = isMiniScreen()
      menuClass.value = isExpandWhenInMobile()
    })()
  }

  // 刷新或者 go back 的时候默认展开菜单
  appStore.isExpand = true
})

function isExpandWhenInMobile(): string {
  return !appStore.isExpand && isMobile.value ? 'hidden' : ''
}
</script>

<style scoped>
.sider-bg {
  background-color: var(--sider-menu-bg-color);
}
</style>
