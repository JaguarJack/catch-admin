<template>
  <div class="flex flex-row h-16 w-full drop-shadow border-l dark:border-l-0 border-gray-200" style="background-color: var(--header-bg-color)">
    <div class="flex flex-row justify-between w-full">
      <div class="flex flex-row min-w-[17rem]">
        <div class="h-full flex items-center w-8 ml-2 hover:cursor-pointer" @click="store.changeExpaned">
          <Icon name="list-bullet" class="w-6 h-8" />
        </div>
        <div class="w-96 flex items-center pl-3 sm:pl-0">
          <Breadcrumbs />
        </div>
      </div>

      <div class="flex w-52 sm:min-w-[18rem] flex-row item-center pl-1 sm:pl-0 justify-end sm:justify-between mr-4">
        <div class="w-3/5 hidden sm:flex">
          <!-- 搜索 -->
          <MenuSearch />
          <!-- 多语言 -->
          <Lang />
          <!-- 暗黑主题 -->
          <Theme />
          <Notification />
        </div>
        <Profile />
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useAppStore } from '/admin/stores/modules/app'
import Notification from './notification.vue'
import MenuSearch from './menuSearch.vue.vue'

const store = useAppStore()
</script>
