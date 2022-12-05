<template>
  <div>
    <div class="bg-white dark:bg-regal-dark rounded flex flex-col sm:flex-row justify-between">
      <div class="w-full sm:w-[40rem] flex flex-row p1-1 sm:pl-3 items-center justify-between min-h-28 border-b pb-2 sm:pb-0 sm:border-b-0 border-gray-400">
        <img :src="avatar" class="rounded-full w-16 h-16" />
        <div class="flex flex-col w-[34rem] pl-4 sm:pl-0 pt-2 sm:pt-3">
          <div class="text-lg text-zinc-800 dark:text-gray-200">很高兴见到你👋 ，{{ nickname }}！{{ itsTimeDo }}</div>
          <div class="text-sm text-gray-400 break-words pt-0 sm:pt-2">{{ context }}</div>
        </div>
      </div>

      <div class="flex items-center h-28 w-full sm:w-[23rem] justify-between pl-2 pr-2 sm:pr-3">
        <div class="flex flex-col text-center">
          <div class="text-lg text-gray-600 dark:text-gray-400">项目数</div>
          <div class="text text-gray-400 dark:text-gray-200">1000</div>
        </div>

        <el-divider direction="vertical" />
        <div class="flex flex-col text-center">
          <div class="text-lg text-gray-600 dark:text-gray-400">国内排名</div>
          <div class="text text-gray-400 dark:text-gray-200">1000</div>
        </div>
        <el-divider direction="vertical" border-style="dashed" />
        <div class="flex flex-col text-center">
          <div class="text-lg text-gray-600 dark:text-gray-400">团队成员</div>
          <div class="text text-gray-400 dark:text-gray-200">1000</div>
        </div>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row mt-4 justify-between">
      <Introduce />
      <Project />
    </div>
  </div>
</template>
<script lang="ts" setup>
import { computed } from 'vue'
import { useUserStore } from '/admin/stores/modules/user'
import Introduce from './introduce.vue'
import Project from './project.vue'

const userStore = useUserStore()

const nickname = computed(() => {
  return userStore.getNickname
})

const avatar = computed(() => {
  return userStore.getAvatar
})
const itsTimeDo = computed(() => {
  const date = new Date()
  const now = date.getHours()
  if (isInRange(now, 2, 5)) {
    return '凌晨了，该休息了！注意身体！😪'
  } else if (isInRange(now, 5, 8)) {
    return '早晨，开始全新的一天！😊'
  } else if (isInRange(now, 8, 12)) {
    return '上午好，开始摸鱼的一天！😄'
  } else if (isInRange(now, 12, 18)) {
    return '下午好，快要下班了！再坚持下💪'
  } else if (isInRange(now, 18, 23)) {
    return '晚上了，请点击右上角关闭！👉'
  } else {
    return '深夜了，为什么还在打开该系统?💢'
  }
})

const context = computed(() => {
  const contexts: string[] = [
    '资本主义社会里的民主是一种残缺不全的，贫乏和虚伪和民主，是只供富人，只供少数人享受的民主',
    '资本来到世间，从头到脚，每个毛孔都滴着血和肮脏的东西',
    '既然掠夺给少数人造成了天然的权利，那么多数人就只得积聚足够的力量，来取得夺回他们被夺去的一切的天然权利',
    '资本家有百分之五十的利润，就会铤而走险；有了百分之一百的利润就敢践踏人间一切法律；有了百分之三百的利润就敢冒上绞刑架的危险',
  ]

  return contexts[Math.floor(Math.random() * contexts.length)]
})

function isInRange(compare: number, min: number, max: number) {
  return compare >= min && compare < max
}
</script>
