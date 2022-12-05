<template>
  <el-form :model="formData" label-width="120px" ref="form" v-loading="loading" class="pr-4">
    <el-form-item
      label="昵称"
      prop="username"
      :rules="[
        {
          required: true,
          message: '昵称必须填写',
        },
      ]"
    >
      <el-input v-model="formData.username" placeholder="请填写昵称" />
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
      <el-input v-model="formData.email" placeholder="请填写邮箱" />
    </el-form-item>
    <el-form-item label="密码" prop="password" :rules="passwordRules">
      <el-input v-model="formData.password" type="password" show-password placeholder="请输入密码" />
    </el-form-item>
    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">{{ $t('system.confirm') }}</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { useCreate } from '/admin/composables/curd/useCreate'
import { useShow } from '/admin/composables/curd/useShow'

import { onMounted, watch, ref } from 'vue'

const props = defineProps({
  primary: String | Number,
  api: String,
})

const passwordRules = [
  {
    required: true,
    message: '密码必须填写',
  },
  {
    pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/,
    message: '必须包含大小写字母和数字的组合，可以使用特殊字符，长度在6-20之间',
  },
]

if (props.primary) {
  passwordRules.shift()
}

const { formData, form, loading, submitForm, isClose } = useCreate(props.api, props.primary)

const emit = defineEmits(['close'])
watch(isClose, function (value) {
  if (value) {
    emit('close')
  }
})

onMounted(() => {
  if (props.primary) {
    useShow(props.api, props.primary).then(r => {
      formData.value = r.data
    })
  }
})
</script>
