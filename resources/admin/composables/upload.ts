import { ref } from 'vue'
import { getFileExt, getFilename } from '/admin/support/helper'
import { Code } from '/admin/enum/app'
import Message from '/admin/support/message'
import { genFileId } from 'element-plus'
import type { UploadInstance, UploadProps, UploadRawFile } from 'element-plus'

// 上传文件
export function uploadFile(action: string, ext: Array<String>, isValidate: boolean = true) {
  const upload = ref<UploadInstance>()

  const file = ref<string>('')
  const filename = ref<string>('')
  const fileExtensions = ext.join('|')

  // 上传前的钩子 判断文件类型
  const beforeUpload = (file: UploadRawFile) => {
    if (isValidate) {
        const isCanUpload = ext.indexOf(getFileExt(file.name).substring(1)) > -1
      if (!isCanUpload) {
        Message.error('不符合上传文件类型，仅支持' + fileExtensions)
      }
      return isCanUpload
    } else {
      return true
    }
  }

  const handleExceed: UploadProps['onExceed'] = files => {
    upload.value!.clearFiles()
    const file = files[0] as UploadRawFile
    file.uid = genFileId()
    upload.value!.handleStart(file)
    upload.value!.submit()
  }

  const handleSuccess = (response: any, uploadFile: any) => {
    if (response.code === Code.SUCCESS) {
      file.value = response.data.path
      filename.value = getFilename(file.value)
    } else {
      Message.error(response.message)
    }
  }

  return { upload, beforeUpload, handleExceed, handleSuccess, file, filename, fileExtensions }
}

// 上传图片
// 上传文件
export function uploadImage(action: string, extensions: Array<String>) {
  const upload = ref<UploadInstance>()

  const file = ref<string>('')
  const filename = ref<string>('')
  const fileExtensions = extensions

  // 上传前的钩子 判断文件类型
  const beforeUpload = (file: UploadRawFile) => {
    const isCanUpload = fileExtensions.indexOf(getFileExt(file.name).substring(1)) > -1
    if (!isCanUpload) {
      Message.error('不符合上传文件类型，仅支持' + fileExtensions)
    }
    return isCanUpload
  }

  const handleExceed: UploadProps['onExceed'] = files => {
    upload.value!.clearFiles()
    const file = files[0] as UploadRawFile
    file.uid = genFileId()
    upload.value!.handleStart(file)
    upload.value!.submit()
  }

  const handleSuccess = (response: any, uploadFile: any) => {
    if (response.code === Code.SUCCESS) {
      file.value = response.data.path
      filename.value = getFilename(file.value)
    } else {
      Message.error(response.message)
    }
  }

  return { upload, beforeUpload, handleExceed, handleSuccess, file, filename, fileExtensions }
}
