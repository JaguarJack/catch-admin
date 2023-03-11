<template>
  <el-breadcrumb separator="/" class="flex sm:text-sm lg:text-base">
    <transition-group name="breadcrumb">
      <!--<el-breadcrumb-item :to="{ path: '/' }" class="text-blue=">Dashboard</el-breadcrumb-item>-->

      <el-breadcrumb-item v-for="(item, index) in breadcrumbs" :key="index" class="text">{{ item }}</el-breadcrumb-item>
    </transition-group>
  </el-breadcrumb>
</template>

<script lang="ts" setup>
import router from '/admin/router'
import { watch, onMounted, ref } from 'vue'
import { useAppStore } from '/admin/stores/modules/app'
import { RouteLocationNormalizedLoaded } from 'vue-router'

const appStore = useAppStore()
const breadcrumbs = ref<string[]>([])

// 监听当前路由的变化
watch(router.currentRoute, (newValue, oldValue) => {
  // 激活菜单
  if (newValue.meta.active_menu) {
    appStore.setActiveMenu(newValue.meta.active_menu as string)
  }
  setActiveMenu(newValue)
  getBreadcrumbs(newValue)
})

// get init breadcrumb
onMounted(() => {
  setActiveMenu(router.currentRoute.value)
  getBreadcrumbs(router.currentRoute.value)
})

const setActiveMenu = (route: RouteLocationNormalizedLoaded) => {
  if (route.path !== '/') {
    // 如果是内页，并且设置激活菜单
    if (route.meta.active_menu) {
      appStore.setActiveMenu(route.meta.active_menu as string)
    } else {
      appStore.setActiveMenu(route.path)
    }
  }
}

// get breadcrums
function getBreadcrumbs(newRoute: RouteLocationNormalizedLoaded) {
  breadcrumbs.value = []
  breadcrumbs.value.push('首页')
  newRoute.matched.forEach(m => {
    if (m.meta.title !== undefined) {
      breadcrumbs.value.push(m.meta?.title as string)
    }
  })
}
</script>

<style>
.breadcrumb-leave-active {
  transition: all 1s linear;
}

.breadcrumb-leave-to {
  opacity: 0;
  transition: all 0.3s linear;
}
.el-breadcrumb {
  font-size: 13px;
}
</style>
