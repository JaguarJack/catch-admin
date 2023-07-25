<template>
  <el-form :model="formData" label-width="120px" ref="form" v-loading="loading" class="pr-4">
    <el-form-item label="安装方式" prop="type">
      <el-radio-group v-model="formData.type">
        <el-radio-button
          v-for="item in [
            { label: '普通安装', value: 1 },
            { label: 'ZIP 安装', value: 2 },
          ]"
          :key="item.value"
          :label="item.value"
          name="type"
          >{{ item.label }}
        </el-radio-button>
      </el-radio-group>
    </el-form-item>
    <el-form-item
      label="模块名称"
      prop="title"
      :rules="[
        {
          required: true,
          message: '模块名称必须填写',
        },
        {
          validator: (rule: any, value: any, callback: any) => {
            if (! /^[A-Za-z]+$/.test(value)) {
                callback('模块名称只允许大小字母组合')
            } else  {
                callback()
            }
          },
          trigger: 'blur',
        },
      ]"
    >
      <el-select v-model="formData.title" placeholder="选择安装模块">
        <el-option v-for="item in modules" :key="item.value" :label="item.label" :value="item.value" />
      </el-select>
    </el-form-item>
    <el-form-item label="上传 ZIP" prop="file" v-if="formData.type === 2">
      <Upload action="module/upload" :limit="1" accept=".zip" :on-success="moduleUpload">
        <template #trigger>
          <el-button type="primary">选择模块文件</el-button>
        </template>
      </Upload>
    </el-form-item>

    <div class="flex justify-end">
      <el-button type="primary" @click="submitForm(form)">安装</el-button>
    </div>
  </el-form>
</template>

<script lang="ts" setup>
import { useCreate } from '/admin/composables/curd/useCreate'

import { onMounted } from 'vue'
import { Code } from '/admin/enum/app'
import Message from '/admin/support/message'

const { formData, form, loading, submitForm, close } = useCreate('module/install')
formData.value.type = 1

const emit = defineEmits(['close'])

onMounted(() => {
  close(() => emit('close'))
})

const moduleUpload = (response, uploadFile) => {
  if (response.code === Code.SUCCESS) {
    formData.value.file = response.data
  } else {
    Message.error(response.message)
  }
}

const modules = [
  {
    label: '权限管理',
    value: 'permissions',
  },
  {
    label: '内容管理',
    value: 'cms',
  },
  {
    label: '系统管理',
    value: 'system',
  },
]
</script>
