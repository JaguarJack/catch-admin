<template>
  <div class="flex w-2/5 hover:cursor-pointer pl-1 pr-1">
    <el-dropdown size="large" placement="bottom-end" class="flex items-center justify-center hover:cursor-pointer w-full">
      <div class="flex lg:items-center">
        <img :src="userStore.getAvatar" class="w-7 h-7 rounded-full" />
        <div class="ml-2 hidden lg:block">{{ userStore.getNickname }}</div>
      </div>
      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item>Action 1</el-dropdown-item>
          <el-dropdown-item>Action 2</el-dropdown-item>
          <el-dropdown-item>Action 3</el-dropdown-item>
          <el-dropdown-item divided @click="logout">
            <Icon name="logout" class="mr-1" />
            退 出
          </el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
  </div>
</template>

<script lang="ts" setup>
import { useUserStore } from '/admin/stores/modules/user'
import Message from '/admin/support/message'

const userStore = useUserStore()

const logout = () => {
  Message.confirm('确定退出系统吗?', () => {
    userStore.logout()
  })
}
</script>
