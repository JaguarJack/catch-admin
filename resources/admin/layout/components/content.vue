<template>
  <div :class="'w-full h-screen flex flex-col transition-spacing duration-300 ease-linear overflow-hidden ' + mlClass">
    <!-- Header -->
    <Header />
    <!-- Tag view -->
    <!--<div class=""></div>-->
    <!-- Container -->
    <div class="p-1 sm:p-3 max-w-full h-screen overflow-auto sm:overflow-x-hidden">
        <div class="min-h-[calc(100vh-8rem)]">
            <router-view />
        </div>
        <div class="w-full text-center text-gray-400 h-4 leading-10">
            <el-link href="https://catchadmin.com/" target="_blank">CatchAdmin 管理系统 </el-link> @copyright 2018 ~ {{ year }}
        </div>
    </div>
  </div>
</template>
<script lang="ts" setup>
import { computed } from 'vue'
import { useAppStore } from '/admin/stores/modules/app'

const appStore = useAppStore()

const mlClass = computed(() => {
  return appStore.isExpand ? 'ml-0 sm:ml-56' : 'ml-0 sm:ml-16'
})

const year = computed(() => {
  const date = new Date()

  return date.getFullYear()
})
</script>
