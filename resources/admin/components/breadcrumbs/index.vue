<template>
  <el-breadcrumb separator="/" class="flex">
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
  // 如果是内页，则不切换激活菜单
  if (newValue.meta.is_inner === undefined || !newValue.meta.is_inner) {
    appStore.setActiveMenu(newValue.path)
  }
  getBreadcrumbs(newValue)
})

// get init breadcrumb
onMounted(() => {
  if (router.currentRoute.value.path !== '/') {
    appStore.setActiveMenu(router.currentRoute.value.path)
  }

  getBreadcrumbs(router.currentRoute.value)
})

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
</style>
