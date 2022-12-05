<template>
  <div class="bg-gray-50 h-screen flex items-center justify-center">
    <div class="flex w-full sm:w-[32rem] shadow bg-white lg:rounded-lg">
      <!--<div class="w-1/2 hidden sm:block">
              <img src="@/assets/login-left.png" />
            </div>-->
      <div class="w-full mx-auto pt-6 pb-6 pl-4 pr-4">
        <div class="flex mt-2">
          <img :src="logo" class="mx-auto w-8" />
        </div>
        <div class="w-full text-center text-2xl mt-6 mb-8 text-indigo-700">Hi, {{ $t('login.welcome') }}</div>
        <el-divider>{{ $t('login.sign_in') }}</el-divider>
        <div>
          <el-form
            ref="form"
            :model="params"
            status-icon
            v-loading.fullscreen.lock="loading"
            :rules="rules"
            element-loading-background="rgba(0, 0, 0, 0.7)"
            label-width="70px"
            class="w-11/12 sm:w-4/5 pt-2 space-y-8 mx-auto"
          >
            <el-form-item prop="email">
              <el-input v-model="params.email" type="email" autocomplete="off" :placeholder="$t('login.email')" size="large" :prefix-icon="Message" class="h-12 text-base" />
            </el-form-item>
            <el-form-item prop="password">
              <el-input v-model="params.password" type="password" autocomplete="off" size="large" :placeholder="$t('login.password')" show-password :prefix-icon="Lock" class="h-12 text-base" />
            </el-form-item>
          </el-form>
        </div>
        <div class="flex justify-between w-11/12 sm:w-4/5 mx-auto mt-3">
          <el-checkbox v-model="params.remember" class="top-2">
            {{ $t('login.remember') }}
          </el-checkbox>
          <div class="text-sm pt-3 text-indigo-600 cursor-pointer">
            {{ $t('login.lost_password') }}
          </div>
        </div>
        <div class="w-11/12 sm:w-4/5 mx-auto mt-5">
          <el-button type="primary" @click="submit(form)" size="large" class="w-full text-xl">
            {{ $t('login.sign_in') }}
          </el-button>
        </div>

        <div class="w-full text-center text-sm text-gray-400 mt-8 mb-10">{{ $t('system.name') }} @copyright 2018-{{ new Date().getFullYear() }}</div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { Lock, Message } from '@element-plus/icons-vue'
import { onMounted } from 'vue'
import { useLogin } from './login'
import logo from '/admin/assets/logo.png'

const { params, loading, submit, form, rules } = useLogin()

// set default color-theme light
onMounted(() => {
  document.querySelector('html')?.setAttribute('class', 'light')
})
</script>

<style lang="scss" scoped>
:deep(.el-form-item__content) {
  margin-left: 0 !important;
}

:deep(.el-divider__text) {
  @apply text-xl text-slate-400;
}
</style>
