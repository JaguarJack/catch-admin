<script setup lang="ts">
import { ref, watch } from 'vue'
import { uploadFile } from '/admin/composables/upload'
import {  getFilename } from '/admin/support/helper'
import { Plus, Delete } from '@element-plus/icons-vue'

const props = defineProps({
    action: {
        type: String,
        required: true
    },
    name: {
        type: String,
        default: 'image'
    },
    auto: {
        type: Boolean,
        default: true
    },
    // eslint-disable-next-line vue/no-reserved-props
    class: {
        type: String,
        default: 'w-24 h-24'
    },
    requestFrom: {
        type: String,
        default: 'Dashboard'
    },
    token:{
        type: String,
        required: true
    },
    ext: {
        type: Array,
        default: () => {
            return ['docx', 'pdf', 'txt', 'html', 'zip', 'tar', 'doc', 'css', 'csv', 'ppt', 'xlsx', 'xls', 'xml']
        }
    }
})

// 定义文件 v-model
const filesModel = defineModel({
    type: Array,
    default: [],
    required: true
})
const files = ref([])

const { upload, beforeUpload, handleExceed, handleSuccess, file, filename} = uploadFile(props.action, props.ext)
const delFile = (key: number) => {
    files.value = files.value.filter((item, index) => index !== key)
    filesModel.value = files.value
}
// 更新 model 的 value
watch(
    () => file.value,
    (newValue, oldValue) => {
        files.value.push(newValue)
    }
)

watch(() => files.value, (newValue, oldValue) => {
    filesModel.value = newValue
}, { deep: true, immediate: true })
</script>
<template>
    <el-upload
        ref="upload"
        :action="action"
        multiple
        :show-file-list="false"
        :name="name"
        :auto-upload="auto"
        :headers="{ authorization: token, 'Request-from': requestFrom }"
        v-bind="$attrs"
        :on-exceed="handleExceed"
        :before-upload="beforeUpload"
        :on-success="handleSuccess"
    >
        <div class="flex flex-col w-full">
            <div>
                <div class="flex items-center justify-center w-24 h-12 text-[14px] border border-blue-100 border-dashed rounded">
                    <div>点击上传文件</div>
                </div>
            </div>
            <div class="w-full">
                <div v-for="(item, key) in files" :key="key" class="flex justify-between w-full">
                    <div>{{ getFilename(item) }}</div>
                    <div class="h-8 flex items-center ml-4"><el-icon @click.stop="delFile(key)" class="cursor-pointer"><Delete /></el-icon></div>
                </div>
            </div>
        </div>
    </el-upload>
</template>

