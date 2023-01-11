<template>
  <div :style="bgColor" class="flex flex-col w-full">
    <img :src="notFound" class="w-full sm:w-3/5 m-auto" />
    <div class="mr-auto w-full bottom-0 m-auto">
      <div class="w-full text-center text-base text-gray-400">抱歉，您访问的页面不存在</div>
      <div @click="push('/')" class="text-center w-full mt-2">
        <el-button type="primary">回到首页</el-button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAppStore } from '/admin/stores/modules/app'
import { computed } from 'vue'
import notFound from '/admin/assets/404.png'

const { push } = useRouter()

const dark: string = '#161d31;'
const light: string = 'rgb(241,245,249);'

const appStore = useAppStore()
const bgColor = computed(() => {
  return 'background-color:' + (appStore.getIsDarkMode ? dark : light)
})
</script>
