<template>
  <el-form :model="profile" ref="form" v-loading="loading" label-position="top">
    <el-upload
      class="w-28 h-28 rounded-full mx-auto"
      action="https://run.mocky.io/v3/9d059bf9-4660-45f2-925d-ce80ad6c4d15"
      :show-file-list="false"
      :on-success="handleAvatarSuccess"
      :before-upload="beforeAvatarUpload"
    >
      <img src="https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg" class="h-28 rounded-full" />
    </el-upload>
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
import { onMounted, ref, unref } from 'vue'
import { useCreate } from '/admin/composables/curd/useCreate'
import http from '/admin/support/http'

interface profile {
  avatar: string
  username: string
  email: string
  password: string
}

const profile = ref<profile>(
  Object.assign({
    avatar: '',
    username: '',
    email: '',
    password: '',
  }),
)

onMounted(() => {
  http.get('user/online').then(r => {
    profile.value.username = r.data.data.username
    profile.value.avatar = r.data.data.avatar
    profile.value.email = r.data.data.email
  })
})
const { form, loading, submitForm } = useCreate('user/online', null, profile)
</script>
