<template>
  <el-form :model="profile" ref="form" v-loading="loading" label-position="top">
    <Upload imageClass="w-28 h-28 rounded-full mx-auto" v-model="profile.avatar" />
    <el-form-item
      label="昵称"
      prop="username"
      class="mt-2"
      :rules="[
        {
          required: true,
          message: '昵称必须填写',
        },
      ]"
    >
      <el-input v-model="profile.username" placeholder="请填写昵称" />
    </el-form-item>
    <el-form-item
      label="邮箱"
      prop="email"
      :rules="[
        {
          required: true,
          message: '邮箱必须填写',
        },
        {
          type: 'email',
          message: '邮箱格式不正确',
        },
      ]"
    >
      <el-input v-model="profile.email" placeholder="请填写邮箱" />
    </el-form-item>
    <el-form-item
      label="密码"
      prop="password"
      :rules="[
        {
          pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/,
          message: '必须包含大小写字母和数字的组合，可以使用特殊字符，长度在6-20之间',
        },
      ]"
    >
      <el-input v-model="profile.password" type="password" show-password placeholder="请输入密码" />
    </el-form-item>
    <div class="flex justify-center">
      <el-button type="primary" @click="submitForm(form)">{{ $t('system.update') }}</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import { useCreate } from '/admin/composables/curd/useCreate'
import http from '/admin/support/http'
import { Code } from '/admin/enum/app'
import Message from '/admin/support/message'
import { useUserStore } from '/admin/stores/modules/user'

interface profile {
  avatar: string
  username: string
  email: string
  password: string
}

const profile = ref<profile>({
  avatar: '',
  username: '',
  email: '',
  password: '',
})
const { form, loading, submitForm, afterCreate } = useCreate('user/online', null, profile)

const getUserInfo = () => {
  loading.value = true
  http.get('user/online').then(r => {
    profile.value.username = r.data.data.username
    profile.value.avatar = r.data.data.avatar
    profile.value.email = r.data.data.email
    loading.value = false
  })
}

onMounted(() => {
  getUserInfo()
})

const userStore = useUserStore()
afterCreate.value = () => {
  userStore.getUserInfo()
}
</script>

<style scoped lang="scss">
:deep(.el-upload) {
  @apply h-full w-full;
}
</style>
