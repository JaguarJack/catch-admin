<template>
  <el-form :model="formData" label-width="80px" ref="form" v-loading="loading" class="pr-4">
    <div class="flex flex-row justify-between">
      <div :class="hasRoles ? 'w-1/2' : 'w-full'">
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
      </div>

      <div class="w-1/2" v-if="hasRoles">
        <el-form-item label="部门" prop="department_id">
          <el-tree-select v-model="formData.department_id" :data="departments" check-strictly :props="{ label: 'department_name', value: 'id' }" />
        </el-form-item>
        <el-form-item label="岗位" prop="department_id">
          <el-select v-model="formData.jobs" multiple>
            <el-option v-for="item in jobs" :key="item.id" :label="item.job_name" :value="item.id" />
          </el-select>
        </el-form-item>
      </div>
    </div>
    <el-form-item label="角色" prop="role_id" :rules="[{ required: true, message: '请选择角色' }]">
      <el-tree v-model="formData.role_id" empty-text="" :data="roles" check-strictly :props="{ label: 'role_name', value: 'id' }" show-checkbox class="w-full mt-1" default-checked-keys />
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
import http from '../../../../resources/admin/support/http'

const props = defineProps({
  primary: String | Number,
  api: String,
  hasRoles: {
    type: Boolean,
    default: true,
  },
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

const { formData, form, loading, submitForm, close } = useCreate(props.api, props.primary)

if (props.primary) {
  useShow(props.api, props.primary, formData)
}
const emit = defineEmits(['close'])

const departments = ref()
const jobs = ref()
const roles = ref()

onMounted(() => {
  close(() => emit('close'))

  if (props.hasRoles) {
    http.get('permissions/roles').then(r => {
      roles.value = r.data.data
    })

    http.get('permissions/departments').then(r => {
      departments.value = r.data.data
    })

    http.get('permissions/jobs').then(r => {
      jobs.value = r.data.data
    })
  }
})
</script>
