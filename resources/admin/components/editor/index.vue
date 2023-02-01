<template>
    <div>
        <Editor :api-key="aipKey" :init="config" v-model="content"/>
    </div>
</template>

<script setup lang="ts">
import Editor from "@tinymce/tinymce-vue";
import { env } from '/admin/support/helper'
import Http from "/admin/support/http"
import Message from '/admin/support/message'
import {ref, watch, watchEffect} from "vue";

const props = defineProps({
    value: {
        type: String,
        default: '',
        require: true,
    },
    width: {
        type: [Number, String],
        required: false,
        default: 'auto'
    },
    height: {
        type: [Number, String],
        required: false,
        default: 'auto'
    },
    language: {
        type: String,
        default: 'zh_CN'
    },

    placeholder: {
        type: String,
        default: '在这里输入内容'
    },

    plugins: {
        type: String,
        default: 'preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template code ' +
                'codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount autosave emoticons'
    },
    toolbar: {
        type: Array,
        default: ["undo redo restoredraft cut copy paste pastetext forecolor backcolor bold italic underline strikethrough link anchor alignleft aligncenter alignright alignjustify outdent indent bullist numlist blockquote subscript superscript removeformat styleselect formatselect fontselect fontsizeselect " +
                "table upload image axupimgs media emoticons charmap hr pagebreak insertdatetime  " +
            "selectall visualblocks searchreplace code print preview indent2em fullscreen"]
    }
})
const aipKey: string = 's1ntkmnev0ggx0hhaqnubrdxhv0ly99uyrdbckeaycx7iz6v'
const uploaded = (blobInfo, progress) => new Promise((resolve, reject) => {
    if(blobInfo.blob().size/1024/1024 > 10){
        Message.error('上传失败，图片大小请控制在 10M 以内')
    } else {
        let params = new FormData()
        params.append('image', blobInfo.blob())
        Http.post(env('VITE_BASE_URL') + 'upload/image', params).then(res=>{
            console.log(res)
            if (res.data.code === 10000) {
                resolve(res.data.data.path)
            } else {
                Message.error(res.message)
            }
        }).catch(()=>{
            Message.error('Server Error!')
        })
    }
})
const config = {
    language: props.language,
    placeholder: props.placeholder,
    width: props.width,
    height: props.height,
    plugins: props.plugins,
    toolbar: props.toolbar,
    branding: false,
    // menubar: false,
    images_upload_handler: uploaded
}

const emits = defineEmits(['update:value'])
const content = ref(props.value)
watch(content, (value) => {
    emits('update:value',value);
})

</script>

<style scoped>
.tinymce-boxz > textarea {
    display: none;
}
</style>
<style>
/* 隐藏apikey没有绑定这个域名的提示 */
.tox-notifications-container .tox-notification--warning {
    display: none !important;
}

.tox{z-index:9999!important;}
</style>
