<template>
    <div class="grid grid-cols-4 gap-2">
        <div v-for="(item, key) in images" :key="key" class="relative w-24 h-24 group">
            <img :src="item" class="w-full h-full" />
            <div class="w-full h-full absolute top-0 left-0 bg-black bg-opacity-50 z-10 hidden group-hover:block" >
            </div>
            <div class="absolute top-9 left-10 text-white z-50" >
                <el-icon @click.prevent="delImage(key)" class="cursor-pointer"><Delete /></el-icon>
            </div>
        </div>
        <div>
            <el-upload
                :action="action"
                :show-file-list="false"
                :name="name"
                multiple
                :headers="{ authorization: token, 'Request-from': requestFrom }"
                ref="upload"
                :on-exceed="handleExceed"
                :on-success="handleSuccess"
                :before-upload="beforeUpload"
            >
                <div class="flex items-center justify-center w-24 h-24 border border-collapse">
                    <el-icon><Plus /></el-icon>
                </div>
            </el-upload>
        </div>
    </div>
</template>
<script setup lang="ts">
import { ref, watch } from 'vue'
import { uploadImage } from '/admin/composables/upload'
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
        default: 'w-24 h-24 p-2'
    },
    requestFrom: {
        type: String,
        default: 'Dashboard'
    },
    multiple: {
        type: Boolean,
        default: false
    },
    token:{
        type: String,
        required: true
    },
    ext: {
        type: Array,
        default: () => ['jpg', 'jpeg', 'png', 'bmp', 'gif']
    }
})

// 定义文件 v-model
const filesModel = defineModel({
    type: Array<String>,
    default: [],
    required: true
})
const images = ref([])
const { upload, beforeUpload, handleExceed, handleSuccess, file } = uploadImage(props.action, props.ext)
const delImage = (key: number) => {
    images.value = images.value.filter((item, index) => index !== key)
    filesModel.value = images.value
}
// 更新 model 的 value
watch(
    () => file.value,
    (newValue, oldValue) => {
        images.value.push(newValue)
    }
)

watch(() => images.value, (newValue, oldValue) => {
    filesModel.value = newValue
}, { deep: true, immediate: true })
</script>

