<template>
  <div class="flex flex-col sm:flex-row dark:bg-regal-dark w-full">
    <el-card shadow="never" class="w-full sm:w-[35rem] h-[32rem]">
      <template #header>
        <div class="card-header">
          <span>个人资料</span>
        </div>
      </template>
      <div class="flex flex-col w-full">
        <div class="w-full">
          <Profile />
        </div>
      </div>
    </el-card>

    <el-tabs v-model="activeName" class="pl-3 pr-3 bg-white dark:bg-regal-dark mt-2 sm:mt-0 w-full ml-0 sm:ml-2">
      <el-tab-pane label="登录日志" name="login_log">
        <LoginLog />
      </el-tab-pane>
      <el-tab-pane label="操作日志" name="operation_log">
        <OperateLog />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import Profile from './components/profile.vue'
import LoginLog from './components/loginLog.vue'
import OperateLog from './components/operateLog.vue'

const activeName = ref('login_log')
</script>

<style scoped>
.el-tabs {
  --el-tabs-header-height: 62px !important;
}

.el-tabs .el-tabs__item {
  font-size: 18px !important;
}
</style>
