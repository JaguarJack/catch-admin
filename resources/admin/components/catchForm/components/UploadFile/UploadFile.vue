<script setup lang="ts">
import { watch } from 'vue'
import { uploadFile } from '/admin/composables/upload'
const props = defineProps({
    action: {
        type: String
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
const fileModel = defineModel({
    type: String,
    default: '',
    required: true
})
const { upload, beforeUpload, handleExceed, handleSuccess, file, filename} = uploadFile(props.action, props.ext)
// 更新 model 的 value
watch(
    () => file.value,
    (newValue, oldValue) => {
        fileModel.value = newValue
    }
)
</script>
<template>
    <el-upload
        ref="upload"
        :action="action"
        :show-file-list="false"
        :name="name"
        :on-exceed="handleExceed"
        :auto-upload="auto"
        :headers="{ authorization: token, 'Request-from': requestFrom }"
        v-bind="$attrs"
        :before-upload="beforeUpload"
        :on-success="handleSuccess"
        :limit="1"
    >
        <div v-if="fileModel">
            {{ filename }}
        </div>
        <div v-else>
            <div class="flex items-center justify-center w-24 h-12 text-[14px] border border-blue-100 border-dashed rounded">
                <div>点击上传文件</div>
            </div>
        </div>
    </el-upload>
</template>

<style scoped lang="scss">

</style>
